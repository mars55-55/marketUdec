<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Review;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class TestReviewSubmission extends Command
{
    protected $signature = 'test:review-submit {--from= : ID del usuario que da la reseña} {--to= : ID del usuario que recibe la reseña} {--rating= : Calificación 1-5} {--comment= : Comentario}';
    protected $description = 'Prueba el envío de una reseña';

    public function handle()
    {
        $fromId = $this->option('from') ?: 2;
        $toId = $this->option('to') ?: 3;
        $rating = $this->option('rating') ?: 5;
        $comment = $this->option('comment') ?: 'Esta es una reseña de prueba desde comando';

        $from = User::find($fromId);
        $to = User::find($toId);

        if (!$from || !$to) {
            $this->error('Usuario no encontrado');
            return 1;
        }

        if ($fromId == $toId) {
            $this->error('No se puede reseñar a sí mismo');
            return 1;
        }

        // Verificar si ya existe reseña
        $existing = Review::where('reviewer_id', $fromId)
                         ->where('reviewed_id', $toId)
                         ->whereNull('listing_id')
                         ->first();

        if ($existing) {
            $this->error('Ya existe una reseña general de este usuario');
            return 1;
        }

        // Crear reseña
        Review::create([
            'reviewer_id' => $fromId,
            'reviewed_id' => $toId,
            'listing_id' => null,
            'rating' => $rating,
            'comment' => $comment,
            'is_public' => true,
        ]);

        $this->info("✅ Reseña creada: {$from->name} → {$to->name} ({$rating} estrellas)");
        
        // Actualizar rating
        $to->updateRating();
        $to->refresh();
        
        $this->info("📊 Nuevo rating de {$to->name}: {$to->rating}/5.0 ({$to->rating_count} reseñas)");

        return 0;
    }
}