<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Listing;
use App\Models\User;
use App\Notifications\ListingBlockedNotification;

class TestListingBlockedNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:listing-blocked';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba las notificaciones de anuncios bloqueados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚫 Probando notificaciones de anuncios bloqueados...');
        
        // Buscar un anuncio activo
        $listing = Listing::where('status', 'active')->first();
        
        if (!$listing) {
            $this->error('❌ No se encontraron anuncios activos para probar.');
            return 1;
        }

        $this->info("📦 Anuncio seleccionado: {$listing->title}");
        $this->info("👤 Propietario: {$listing->user->name}");
        
        // Simular bloqueo con razón
        $reason = 'Anuncio bloqueado para pruebas del sistema de notificaciones';
        
        // Enviar notificación
        $listing->user->notify(new ListingBlockedNotification($listing, $reason));
        
        $this->info('✅ Notificación de anuncio bloqueado enviada!');
        $this->info("📧 Usuario notificado: {$listing->user->name}");
        $this->info("📩 Puedes revisar las notificaciones en: /notifications/page");
        
        return 0;
    }
}
