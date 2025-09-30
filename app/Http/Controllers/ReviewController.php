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
        ], [
            'user_id.required' => 'Se requiere seleccionar un usuario para calificar.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'listing_id.exists' => 'El anuncio seleccionado no existe.',
            'rating.required' => 'Debes seleccionar una calificación de 1 a 5 estrellas.',
            'rating.integer' => 'La calificación debe ser un número entero.',
            'rating.min' => 'La calificación mínima es 1 estrella.',
            'rating.max' => 'La calificación máxima es 5 estrellas.',
            'comment.max' => 'El comentario no puede exceder los 1000 caracteres.',
        ]);

        $user = Auth::user();
        
        // Verificar que no esté tratando de reseñarse a sí mismo
        if ($request->user_id == $user->id) {
            return back()->with('error', '❌ No puedes dejarte una reseña a ti mismo. Solo otros usuarios pueden calificarte.');
        }

        // Verificar si ya tiene una reseña para este usuario/anuncio
        $existingReview = Review::where('reviewer_id', $user->id)
            ->where('reviewed_id', $request->user_id)
            ->where('listing_id', $request->listing_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', '⚠️ Ya has dejado una reseña para esta transacción. No puedes calificar al mismo usuario más de una vez por anuncio.');
        }

        // Crear la reseña
        Review::create([
            'reviewer_id' => $user->id,
            'reviewed_id' => $request->user_id,
            'listing_id' => $request->listing_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Actualizar el rating promedio del usuario usando el método del modelo
        $reviewedUser = User::findOrFail($request->user_id);
        $reviewedUser->updateRating();

        return back()->with('success', '🎉 ¡Reseña publicada exitosamente! Tu calificación de ' . $request->rating . ' estrellas ha sido registrada. ¡Gracias por compartir tu experiencia!');
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
}
