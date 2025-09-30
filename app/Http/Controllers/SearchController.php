<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::with(['user', 'category', 'primaryImage'])
            ->whereIn('status', ['active', 'sold'])
            ->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END");

        // Búsqueda por palabra clave
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filtro por categoría
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtro por rango de precio
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filtro por condición
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Filtro por ubicación
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', "%{$request->location}%");
        }

        // Ordenamiento
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'views':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
        }

        $listings = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        // Contar resultados para mostrar mensaje cuando no hay coincidencias
        $totalResults = $listings->total();

        return view('search.index', compact('listings', 'categories', 'totalResults'));
    }

    public function autocomplete(Request $request)
    {
        if (!$request->filled('q')) {
            return response()->json([]);
        }

        $searchTerm = $request->q;
        
        $suggestions = Listing::active()
            ->where('title', 'LIKE', "%{$searchTerm}%")
            ->select('title')
            ->distinct()
            ->limit(10)
            ->pluck('title');

        return response()->json($suggestions);
    }
}
