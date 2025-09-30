<?php

namespace App\Console\Commands;

use App\Models\Review;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Console\Command;

class CreateRealisticReviews extends Command
{
    protected $signature = 'reviews:realistic {--user= : ID del usuario para el que crear reseñas} {--count=5 : Número de reseñas a crear}';
    protected $description = 'Crea reseñas realistas y variadas';

    private $positiveComments = [
        '¡Excelente vendedor! Todo llegó perfecto y muy rápido.',
        'Muy buena comunicación y el producto estaba como se describía.',
        'Persona de confianza, la transacción fue muy fluida.',
        'Producto en excelente estado, mejor de lo que esperaba.',
        '10/10, vendedor muy responsable y honesto.',
        'Entrega rápida y segura. Altamente recomendado.',
        'Buena calidad del producto y excelente trato.',
        'Todo perfecto, sin complicaciones. Gracias!',
        'Vendedor serio y confiable. Volveré a comprar.',
        'El producto superó mis expectativas. Genial!'
    ];

    private $neutralComments = [
        'La transacción fue correcta, sin problemas.',
        'Todo bien, aunque tardó un poco en responder.',
        'Producto como se describía, entrega normal.',
        'Correcto, sin más. Cumplió lo acordado.',
        'Bien en general, algunas demoras menores.',
        'Transacción estándar, todo en orden.',
        'El producto estaba bien, servicio normal.',
        'Cumplió con lo acordado, tiempo de respuesta regular.',
    ];

    private $negativeComments = [
        'El producto tenía algunos detalles no mencionados.',
        'Tardó mucho en responder los mensajes.',
        'El estado del producto no era exactamente como se describía.',
        'Hubo algunas complicaciones en la entrega.',
        'Comunicación algo deficiente, pero se resolvió.',
        'El producto tenía más uso del que se indicaba.',
    ];

    public function handle()
    {
        $userId = $this->option('user');
        $count = (int) $this->option('count');

        if (!$userId) {
            $this->error('Debes especificar un usuario con --user=ID');
            return 1;
        }

        $user = User::find($userId);
        if (!$user) {
            $this->error("Usuario con ID {$userId} no encontrado");
            return 1;
        }

        $this->info("Creando {$count} reseñas realistas para {$user->name}...");

        // Obtener otros usuarios (excluyendo el usuario objetivo)
        $reviewers = User::where('id', '!=', $userId)->get();
        
        if ($reviewers->count() === 0) {
            $this->error('No hay otros usuarios disponibles para crear reseñas');
            return 1;
        }

        // Obtener algunos anuncios del usuario
        $listings = Listing::where('user_id', $userId)->get();

        for ($i = 0; $i < $count; $i++) {
            $reviewer = $reviewers->random();
            $listing = $listings->count() > 0 ? ($i < 3 ? $listings->random() : null) : null;
            
            // Distribución de ratings más realista: más 4s y 5s, algunos 3s, pocos 1s y 2s
            $ratingDistribution = [5, 5, 5, 4, 4, 4, 4, 3, 3, 2];
            $rating = $ratingDistribution[array_rand($ratingDistribution)];
            
            // Seleccionar comentario según el rating
            if ($rating >= 4) {
                $comment = $this->positiveComments[array_rand($this->positiveComments)];
            } elseif ($rating == 3) {
                $comment = $this->neutralComments[array_rand($this->neutralComments)];
            } else {
                $comment = $this->negativeComments[array_rand($this->negativeComments)];
            }

            // Verificar que no exista ya esta combinación
            $exists = Review::where('reviewer_id', $reviewer->id)
                          ->where('reviewed_id', $userId)
                          ->where('listing_id', $listing?->id)
                          ->exists();

            if ($exists) {
                $this->line("Saltando reseña duplicada de {$reviewer->name}");
                continue;
            }

            Review::create([
                'reviewer_id' => $reviewer->id,
                'reviewed_id' => $userId,
                'listing_id' => $listing?->id,
                'rating' => $rating,
                'comment' => $comment,
                'is_public' => true,
            ]);

            $listingInfo = $listing ? "sobre '{$listing->title}'" : 'general';
            $this->line("✅ Reseña creada: {$rating} estrellas de {$reviewer->name} {$listingInfo}");
        }

        // Actualizar rating del usuario
        $user->updateRating();
        $user->refresh();

        $this->newLine();
        $this->info("🎉 ¡Listo! Nuevo rating de {$user->name}: {$user->rating}/5.0 ({$user->rating_count} reseñas)");
        
        return 0;
    }
}