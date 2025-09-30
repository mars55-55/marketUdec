<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Listing;
use App\Models\Report;
use App\Models\Review;
use App\Models\Category;
use App\Notifications\ListingBlockedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        // Solo usuarios admin pueden acceder
        // En un ambiente real, se implementaría un middleware específico
    }

    public function index()
    {
        $pendingReports = Report::where('status', 'pending')->count();
        $totalListings = Listing::count();
        $totalUsers = User::count();
        $activeListings = Listing::where('status', 'active')->count();

        // Reportes recientes
        $recentReports = Report::with(['reporter', 'listing'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'pendingReports',
            'totalListings',
            'totalUsers', 
            'activeListings',
            'recentReports'
        ));
    }

    public function reports(Request $request)
    {
        $query = Report::with(['reporter', 'listing', 'reportedUser']);

        // Filtros
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->type) {
            $query->where('reason', $request->type);
        }

        if ($request->listing_id) {
            $query->where('listing_id', $request->listing_id);
        }

        $reports = $query->latest()->paginate(20);

        // Estadísticas para la vista
        $stats = [
            'pending_reports' => Report::where('status', 'pending')->count(),
            'reviewed_reports' => Report::where('status', 'reviewed')->count(),
            'resolved_today' => Report::where('status', 'resolved')
                ->whereDate('resolved_at', today())
                ->count(),
        ];

        return view('admin.reports', compact('reports', 'stats'));
    }

    public function listings(Request $request)
    {
        $query = Listing::with(['user', 'category'])
                       ->withCount('reports');

        // Filtros
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->user) {
            $query->where('user_id', $request->user);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'LIKE', '%' . $request->search . '%');
                  });
            });
        }

        $listings = $query->latest()->paginate(20);
        $categories = Category::all();

        // Estadísticas
        $stats = [
            'total' => Listing::count(),
            'active' => Listing::where('status', 'active')->count(),
            'blocked' => Listing::where('status', 'blocked')->count(),
            'sold' => Listing::where('status', 'sold')->count(),
        ];

        return view('admin.listings', compact('listings', 'categories', 'stats'));
    }

    public function users(Request $request)
    {
        $query = User::withCount(['listings', 'reviews']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('email', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->campus) {
            $query->where('campus', $request->campus);
        }

        $users = $query->latest()->paginate(20);

        // Estadísticas
        $stats = [
            'total' => User::count(),
            'active' => User::whereHas('listings')->count(),
            'with_listings' => User::has('listings')->count(),
            'new' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('admin.users', compact('users', 'stats'));
    }

    public function categories()
    {
        $categories = Category::withCount('listings')->orderBy('name')->get();
        
        // Estadísticas
        $stats = [
            'total' => Category::count(),
            'with_listings' => Category::has('listings')->count(),
            'most_popular' => Category::withCount('listings')->orderBy('listings_count', 'desc')->first(),
        ];

        return view('admin.categories', compact('categories', 'stats'));
    }

    // Acciones de moderación
    public function blockListing(Listing $listing, Request $request)
    {
        $reason = $request->input('reason', 'Tu anuncio ha sido bloqueado por violar las políticas de la plataforma.');
        
        $listing->update(['status' => 'blocked']);
        
        // Enviar notificación al propietario del anuncio
        $listing->user->notify(new ListingBlockedNotification($listing, $reason));
        
        return back()->with('success', '🚫 Anuncio bloqueado exitosamente. El propietario ha sido notificado.');
    }

    public function unblockListing(Listing $listing)
    {
        $listing->update(['status' => 'active']);
        
        return back()->with('success', 'Anuncio desbloqueado exitosamente.');
    }

    public function resolveReport(Report $report, Request $request)
    {
        $action = $request->input('action', 'approve');

        if ($action === 'approve') {
            // Marcar como aprobado (sin infracción)
            $report->update([
                'status' => 'resolved',
                'moderator_notes' => 'Sin infracción detectada - Reporte aprobado',
                'moderator_id' => Auth::id(),
                'resolved_at' => now(),
            ]);

            if ($report->listing) {
                // Marcar anuncio como aprobado si estaba en revisión
                if ($report->listing->status !== 'active') {
                    $report->listing->update(['status' => 'active']);
                }
            }

            return back()->with('success', '✅ Reporte resuelto: Sin infracción detectada. El anuncio se mantiene activo.');
        }

        // Para otras acciones, validar más campos
        $request->validate([
            'action' => 'required|in:dismiss,block_listing,warn_user,block_user',
            'moderator_notes' => 'nullable|string|max:1000',
        ]);

        $report->update([
            'status' => 'resolved',
            'moderator_notes' => $request->input('moderator_notes', 'Reporte procesado por moderador'),
            'moderator_id' => Auth::id(),
            'resolved_at' => now(),
        ]);

        // Ejecutar acción específica
        switch ($request->action) {
            case 'block_listing':
                if ($report->listing) {
                    $report->listing->update(['status' => 'blocked']);
                    
                    // Enviar notificación al propietario
                    $reason = 'Tu anuncio ha sido bloqueado debido a un reporte: ' . ucfirst($report->reason);
                    $report->listing->user->notify(new ListingBlockedNotification($report->listing, $reason));
                    
                    return back()->with('success', '🚫 Anuncio bloqueado y reporte resuelto. El propietario ha sido notificado.');
                }
                break;
            case 'dismiss':
                return back()->with('success', '📋 Reporte desestimado.');
        }

        return back()->with('success', 'Reporte resuelto exitosamente.');
    }

    // Método para marcar reporte en revisión
    public function reviewReport(Report $report)
    {
        $report->update([
            'status' => 'reviewed',
            'moderator_id' => Auth::id(),
        ]);

        return back()->with('success', '⏳ Reporte marcado como en revisión.');
    }

    public function createCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'icon' => 'nullable|string|max:10',
        ]);

        Category::create([
            'name' => $request->name,
            'icon' => $request->icon ?? '📦',
        ]);

        return back()->with('success', 'Categoría creada exitosamente.');
    }

    public function updateCategory(Category $category, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:10',
        ]);

        $category->update([
            'name' => $request->name,
            'icon' => $request->icon ?? '📦',
        ]);

        return back()->with('success', 'Categoría actualizada exitosamente.');
    }

    public function deleteCategory(Category $category)
    {
        if ($category->listings()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar una categoría que tiene anuncios asociados.']);
        }

        $category->delete();
        
        return back()->with('success', 'Categoría eliminada exitosamente.');
    }
}