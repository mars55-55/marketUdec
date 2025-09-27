<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Página principal - mostrar anuncios recientes
Route::get('/', [ListingController::class, 'index'])->name('home');

// Dashboard del usuario autenticado
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de anuncios (listings)
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');

Route::middleware('auth')->group(function () {
    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing}', [ListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->name('listings.destroy');
    Route::post('/listings/{listing}/toggle-status', [ListingController::class, 'toggleStatus'])->name('listings.toggle-status');
    Route::post('/listings/{listing}/mark-sold', [ListingController::class, 'markAsSold'])->name('listings.mark-sold');
});

// Búsqueda y filtros
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/api/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');

// Favoritos
Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

// Chat/Conversaciones
Route::middleware('auth')->group(function () {
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations', [ConversationController::class, 'store'])->name('conversations.store');
    Route::post('/messages', [ConversationController::class, 'storeMessage'])->name('messages.store');
    Route::get('/conversations/{conversation}/messages/check', [ConversationController::class, 'checkNewMessages'])->name('messages.check');
    Route::post('/conversations/{conversation}/block', [ConversationController::class, 'block'])->name('conversations.block');
    Route::post('/conversations/{conversation}/unblock', [ConversationController::class, 'unblock'])->name('conversations.unblock');
});

// Perfiles de usuario
use App\Http\Controllers\UserController;
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

// Panel de administración (requiere permisos de admin)
use App\Http\Controllers\AdminController;
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/listings', [AdminController::class, 'listings'])->name('listings');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    
    // Acciones de moderación
    Route::post('/listings/{listing}/block', [AdminController::class, 'blockListing'])->name('listings.block');
    Route::post('/listings/{listing}/unblock', [AdminController::class, 'unblockListing'])->name('listings.unblock');
    Route::post('/reports/{report}/resolve', [AdminController::class, 'resolveReport'])->name('reports.resolve');
    Route::post('/categories', [AdminController::class, 'createCategory'])->name('categories.store');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.destroy');
});

// Reviews y calificaciones
Route::middleware('auth')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/users/{user}/reviews', [ReviewController::class, 'show'])->name('reviews.show');
});

// Reportes
use App\Http\Controllers\ReportController;
Route::middleware('auth')->group(function () {
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
});

// Perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
