<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Review;
use Illuminate\Console\Command;

class ReviewSystemStatus extends Command
{
    protected $signature = 'reviews:status';
    protected $description = 'Muestra el estado actual del sistema de reseñas';

    public function handle()
    {
        $this->info('🔍 Estado del Sistema de Reseñas');
        $this->line('=====================================');

        // Estadísticas generales
        $totalReviews = Review::count();
        $totalUsers = User::count();
        $usersWithReviews = User::has('reviews')->count();

        $this->newLine();
        $this->info('📊 Estadísticas Generales:');
        $this->line("• Total reseñas: {$totalReviews}");
        $this->line("• Total usuarios: {$totalUsers}");
        $this->line("• Usuarios con reseñas: {$usersWithReviews}");

        // Distribución de ratings
        $this->newLine();
        $this->info('⭐ Distribución de Calificaciones:');
        for ($rating = 5; $rating >= 1; $rating--) {
            $count = Review::where('rating', $rating)->count();
            $percentage = $totalReviews > 0 ? round(($count / $totalReviews) * 100, 1) : 0;
            $stars = str_repeat('⭐', $rating) . str_repeat('☆', 5 - $rating);
            $this->line("• {$stars} ({$rating}): {$count} reseñas ({$percentage}%)");
        }

        // Top usuarios por rating
        $this->newLine();
        $this->info('🏆 Top Usuarios por Rating:');
        $topUsers = User::has('reviews')
                       ->orderByDesc('rating')
                       ->orderByDesc('rating_count')
                       ->limit(5)
                       ->get();

        foreach ($topUsers as $index => $user) {
            $medal = ['🥇', '🥈', '🥉', '🏅', '🏅'][$index] ?? '⭐';
            $rating = number_format($user->rating, 2);
            $this->line("• {$medal} {$user->name}: {$rating}/5.0 ({$user->rating_count} reseñas)");
        }

        // Reseñas recientes
        $this->newLine();
        $this->info('🕒 Últimas 3 Reseñas:');
        $recentReviews = Review::with(['reviewer', 'reviewed'])
                              ->latest()
                              ->limit(3)
                              ->get();

        foreach ($recentReviews as $review) {
            $stars = str_repeat('⭐', $review->rating);
            $time = $review->created_at->diffForHumans();
            $this->line("• {$stars} {$review->reviewer->name} → {$review->reviewed->name} ({$time})");
            $this->line("  \"{$review->comment}\"");
        }

        // Validación del sistema
        $this->newLine();
        $this->info('✅ Validaciones del Sistema:');
        
        // Verificar auto-reseñas
        $selfReviews = Review::whereColumn('reviewer_id', 'reviewed_id')->count();
        if ($selfReviews > 0) {
            $this->error("❌ Encontradas {$selfReviews} auto-reseñas (usuarios calificándose a sí mismos)");
        } else {
            $this->line('✅ Sin auto-reseñas detectadas');
        }

        // Verificar ratings calculados
        $usersWithIncorrectRating = User::has('reviews')->get()->filter(function($user) {
            $calculatedRating = $user->reviews()->avg('rating');
            return abs($user->rating - $calculatedRating) > 0.01;
        })->count();

        if ($usersWithIncorrectRating > 0) {
            $this->error("❌ {$usersWithIncorrectRating} usuarios con rating incorrecto");
        } else {
            $this->line('✅ Todos los ratings están correctamente calculados');
        }

        // Verificar conteos
        $usersWithIncorrectCount = User::has('reviews')->get()->filter(function($user) {
            return $user->rating_count !== $user->reviews()->count();
        })->count();

        if ($usersWithIncorrectCount > 0) {
            $this->error("❌ {$usersWithIncorrectCount} usuarios con conteo de reseñas incorrecto");
        } else {
            $this->line('✅ Todos los conteos de reseñas están correctos');
        }

        $this->newLine();
        $this->info('🎉 Sistema de reseñas funcionando correctamente!');

        return 0;
    }
}