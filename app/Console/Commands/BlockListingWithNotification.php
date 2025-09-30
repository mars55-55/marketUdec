<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Listing;
use App\Notifications\ListingBlockedNotification;

class BlockListingWithNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:block-listing {id? : ID del anuncio a bloquear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bloquea un anuncio y envía notificación al propietario';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚫 Bloqueando anuncio con notificación...');
        
        $listingId = $this->argument('id');
        
        if ($listingId) {
            $listing = Listing::find($listingId);
        } else {
            // Seleccionar un anuncio activo aleatoriamente
            $listing = Listing::where('status', 'active')->inRandomOrder()->first();
        }
        
        if (!$listing) {
            $this->error('❌ No se encontró el anuncio especificado o no hay anuncios activos.');
            return 1;
        }

        if ($listing->status === 'blocked') {
            $this->warn('⚠️ Este anuncio ya está bloqueado.');
            return 1;
        }

        $this->info("📦 Anuncio: {$listing->title}");
        $this->info("👤 Propietario: {$listing->user->name}");
        $this->info("📍 Estado actual: {$listing->status}");
        
        // Confirmar acción
        if (!$this->confirm('¿Confirmas que quieres bloquear este anuncio?')) {
            $this->info('❌ Operación cancelada.');
            return 0;
        }
        
        $reason = $this->ask('Motivo del bloqueo', 'El anuncio viola las políticas de la plataforma');
        
        // Bloquear anuncio
        $listing->update(['status' => 'blocked']);
        
        // Enviar notificación
        $listing->user->notify(new ListingBlockedNotification($listing, $reason));
        
        $this->info('✅ ¡Anuncio bloqueado exitosamente!');
        $this->info('📧 Notificación enviada al propietario');
        $this->info('🔍 Estado actualizado: blocked');
        
        $this->newLine();
        $this->info('📱 El usuario puede ver la notificación en:');
        $this->info('   • Panel de notificaciones: /notifications/page');
        $this->info('   • Dropdown de notificaciones en la navegación');
        
        return 0;
    }
}
