<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        // Obtener anuncios del usuario (paginados)
        $listings = $user->listings()
            ->with(['category', 'images', 'favorites'])
            ->latest()
            ->paginate(12);

        // Obtener reseñas recientes del usuario
        $reviews = $user->reviews()
            ->with(['reviewer', 'listing'])
            ->latest()
            ->limit(5)
            ->get();

        return view('users.show', compact('user', 'listings', 'reviews'));
    }
}