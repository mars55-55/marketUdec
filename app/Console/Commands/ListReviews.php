<?php

namespace App\Console\Commands;

use App\Models\Review;
use Illuminate\Console\Command;

class ListReviews extends Command
{
    protected $signature = 'reviews:list';
    protected $description = 'Lista todas las reseñas en la base de datos';

    public function handle()
    {
        $reviews = Review::with(['reviewer', 'reviewed', 'listing'])->get();
        
        if ($reviews->count() === 0) {
            $this->info('No hay reseñas en la base de datos.');
            return;
        }

        $this->info('📝 Reseñas en la base de datos:');
        $this->newLine();

        foreach ($reviews as $review) {
            $this->line("ID: {$review->id}");
            $this->line("De: {$review->reviewer->name}");
            $this->line("Para: {$review->reviewed->name}");
            $this->line("Rating: {$review->rating}/5 estrellas");
            $this->line("Anuncio: " . ($review->listing ? $review->listing->title : 'General'));
            $this->line("Comentario: {$review->comment}");
            $this->line("Fecha: {$review->created_at->format('d/m/Y H:i')}");
            $this->line("---");
        }

        $this->newLine();
        $this->info("Total: {$reviews->count()} reseñas");
    }
}