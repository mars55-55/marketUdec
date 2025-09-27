<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Listing;
use App\Models\Report;
use App\Models\Review;
use App\Models\Category;
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
        $stats = [
            'users' => User::count(),
            'listings' => Listing::count(),
            'active_listings' => Listing::where('status', 'active')->count(),
            'reports' => Report::where('status', 'pending')->count(),
            'reviews' => Review::count(),
            'categories' => Category::count(),
        ];

        // Reportes recientes
        $recentReports = Report::with(['reporter', 'listing', 'reportedUser'])
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        // Anuncios recientes
        $recentListings = Listing::with(['user', 'category'])
            ->latest()
            ->limit(10)
            ->get();

        // Usuarios más activos
        $activeUsers = User::withCount(['listings', 'reviews'])
            ->orderBy('listings_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.index', compact('stats', 'recentReports', 'recentListings', 'activeUsers'));
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

        $reports = $query->latest()->paginate(20);

        return view('admin.reports', compact('reports'));
    }

    public function listings(Request $request)
    {
        $query = Listing::with(['user', 'category']);

        // Filtros
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->search . '%');
            });
        }

        $listings = $query->latest()->paginate(20);
        $categories = Category::all();

        return view('admin.listings', compact('listings', 'categories'));
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

        return view('admin.users', compact('users'));
    }

    public function categories()
    {
        $categories = Category::withCount('listings')->get();
        return view('admin.categories', compact('categories'));
    }

    // Acciones de moderación
    public function blockListing(Listing $listing)
    {
        $listing->update(['status' => 'blocked']);
        
        return back()->with('success', 'Anuncio bloqueado exitosamente.');
    }

    public function unblockListing(Listing $listing)
    {
        $listing->update(['status' => 'active']);
        
        return back()->with('success', 'Anuncio desbloqueado exitosamente.');
    }

    public function resolveReport(Report $report, Request $request)
    {
        $request->validate([
            'action' => 'required|in:dismiss,block_listing,warn_user,block_user',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report->update([
            'status' => 'resolved',
            'admin_notes' => $request->admin_notes,
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
        ]);

        // Ejecutar acción
        switch ($request->action) {
            case 'block_listing':
                if ($report->listing) {
                    $report->listing->update(['status' => 'blocked']);
                }
                break;
            case 'warn_user':
                // Implementar sistema de advertencias si es necesario
                break;
            case 'block_user':
                if ($report->reportedUser) {
                    // Implementar bloqueo de usuario si es necesario
                }
                break;
        }

        return back()->with('success', 'Reporte resuelto exitosamente.');
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