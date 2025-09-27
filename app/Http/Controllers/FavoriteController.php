<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

    /**
     * Display user's favorites
     */
    public function index()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->with(['listing.user', 'listing.category', 'listing.primaryImage'])
            ->whereHas('listing', function($query) {
                $query->active(); // Solo mostrar favoritos de anuncios activos
            })
            ->latest()
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    /**
     * Add listing to favorites
     */
    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id'
        ]);

        $listing = Listing::findOrFail($request->listing_id);
        
        // Verificar que el anuncio esté activo
        if ($listing->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede agregar a favoritos un anuncio inactivo.'
            ], 400);
        }

        // Verificar que no sea su propio anuncio
        if ($listing->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes agregar tu propio anuncio a favoritos.'
            ], 400);
        }

        // Crear favorito si no existe (evitar duplicados)
        $favorite = Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'listing_id' => $request->listing_id
        ]);

        if ($favorite->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'Anuncio agregado a favoritos.',
                'action' => 'added'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Este anuncio ya está en tus favoritos.',
                'action' => 'exists'
            ]);
        }
    }

    /**
     * Remove listing from favorites
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id'
        ]);

        $favorite = Favorite::where('user_id', Auth::id())
            ->where('listing_id', $request->listing_id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'success' => true,
                'message' => 'Anuncio eliminado de favoritos.',
                'action' => 'removed'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Este anuncio no está en tus favoritos.',
            'action' => 'not_found'
        ], 404);
    }

    /**
     * Toggle favorite status
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id'
        ]);

        $listing = Listing::findOrFail($request->listing_id);
        
        // Verificaciones
        if ($listing->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede agregar a favoritos un anuncio inactivo.'
            ], 400);
        }

        if ($listing->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes agregar tu propio anuncio a favoritos.'
            ], 400);
        }

        $favorite = Favorite::where('user_id', Auth::id())
            ->where('listing_id', $request->listing_id)
            ->first();

        if ($favorite) {
            // Remover de favoritos
            $favorite->delete();
            return response()->json([
                'success' => true,
                'message' => 'Eliminado de favoritos.',
                'action' => 'removed',
                'is_favorite' => false
            ]);
        } else {
            // Agregar a favoritos
            Favorite::create([
                'user_id' => Auth::id(),
                'listing_id' => $request->listing_id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Agregado a favoritos.',
                'action' => 'added',
                'is_favorite' => true
            ]);
        }
    }
}
