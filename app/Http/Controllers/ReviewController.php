<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'listing_id' => 'nullable|exists:listings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        
        // Verificar que no esté tratando de reseñarse a sí mismo
        if ($request->user_id == $user->id) {
            return back()->withErrors(['error' => 'No puedes dejarte una reseña a ti mismo.']);
        }

        // Verificar si ya tiene una reseña para este usuario/anuncio
        $existingReview = Review::where('reviewer_id', $user->id)
            ->where('user_id', $request->user_id)
            ->where('listing_id', $request->listing_id)
            ->first();

        if ($existingReview) {
            return back()->withErrors(['error' => 'Ya has dejado una reseña para esta transacción.']);
        }

        // Crear la reseña
        Review::create([
            'reviewer_id' => $user->id,
            'user_id' => $request->user_id,
            'listing_id' => $request->listing_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Actualizar el rating promedio del usuario
        $this->updateUserRating($request->user_id);

        return back()->with('success', 'Reseña publicada exitosamente.');
    }

    public function show(User $user, Request $request)
    {
        $reviews = $user->reviews()
            ->with(['reviewer', 'listing'])
            ->when($request->rating, function($query, $rating) {
                return $query->where('rating', $rating);
            })
            ->latest()
            ->paginate(10);

        return view('reviews.show', compact('user', 'reviews'));
    }

    private function updateUserRating($userId)
    {
        $user = User::findOrFail($userId);
        $averageRating = $user->reviews()->avg('rating');
        
        $user->update([
            'rating' => $averageRating ? round($averageRating, 1) : 0
        ]);
    }
}
