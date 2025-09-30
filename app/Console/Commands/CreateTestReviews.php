<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Review;
use App\Models\Listing;

class CreateTestReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-reviews {--user=2}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test reviews for a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user');
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User {$userId} not found!");
            return;
        }

        $this->info("Creating test reviews for {$user->name}...");

        // Obtener algunos listings para asociar con las reseñas
        $listings = Listing::take(3)->get();
        
        $reviewsData = [
            [
                'reviewer_id' => 1,
                'rating' => 5,
                'comment' => '¡Excelente vendedor! El producto llegó en perfectas condiciones y muy rápido. Totalmente recomendado.'
            ],
            [
                'reviewer_id' => 3,
                'rating' => 4,
                'comment' => 'Buen servicio, aunque tardó un poco en responder los mensajes. El artículo estaba como se describía.'
            ],
            [
                'reviewer_id' => 1,
                'rating' => 5,
                'comment' => 'Persona muy confiable. Entrega puntual y producto de calidad. Sin dudas volvería a comprar.'
            ],
            [
                'reviewer_id' => 3,
                'rating' => 3,
                'comment' => 'La transacción fue correcta, pero el producto tenía algunos detalles que no se mencionaron en la descripción.'
            ]
        ];

        foreach ($reviewsData as $index => $reviewData) {
            $listing = $listings->get($index % $listings->count());
            
            $review = Review::create([
                'reviewer_id' => $reviewData['reviewer_id'],
                'reviewed_id' => $userId,
                'listing_id' => $listing ? $listing->id : null,
                'rating' => $reviewData['rating'],
                'comment' => $reviewData['comment'],
                'is_public' => true,
            ]);
            
            $this->line("Created review: {$reviewData['rating']} stars - {$reviewData['comment']}");
        }

        // Actualizar el rating del usuario
        $user->updateRating();
        
        $this->info("✅ Reviews created! New rating: {$user->rating} ({$user->rating_count} reviews)");

        return 0;
    }
}
