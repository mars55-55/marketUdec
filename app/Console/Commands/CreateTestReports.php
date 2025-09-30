<?php

namespace App\Console\Commands;

use App\Models\Report;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Console\Command;

class CreateTestReports extends Command
{
    protected $signature = 'test:create-reports';
    protected $description = 'Crea reportes de prueba para el sistema de moderación';

    public function handle()
    {
        $this->info('🚨 Creando reportes de prueba...');

        $reporters = User::where('is_admin', false)->get();
        $listings = Listing::with('user')->get();

        if ($reporters->count() === 0) {
            $this->error('No hay usuarios no-admin para reportar');
            return 1;
        }

        if ($listings->count() === 0) {
            $this->error('No hay anuncios para reportar');
            return 1;
        }

        // Tipos de reportes
        $reportTypes = [
            ['reason' => 'spam', 'descriptions' => [
                'Este anuncio parece ser spam, se repite constantemente',
                'Publicación duplicada múltiples veces',
                'Contenido no relacionado con lo que se vende'
            ]],
            ['reason' => 'inappropriate', 'descriptions' => [
                'Contenido inapropiado para estudiantes universitarios',
                'Imágenes no relacionadas con el producto',
                'Lenguaje ofensivo en la descripción'
            ]],
            ['reason' => 'scam', 'descriptions' => [
                'Precio sospechosamente bajo para este tipo de producto',
                'El vendedor pide transferencias antes de mostrar el producto',
                'Posible estafa, producto demasiado bueno para ser verdad'
            ]],
            ['reason' => 'fake', 'descriptions' => [
                'El producto parece ser falso o imitación',
                'Imágenes tomadas de internet, no del producto real',
                'Marca falsificada'
            ]],
            ['reason' => 'other', 'descriptions' => [
                'El anuncio no cumple con las políticas de la universidad',
                'Información incorrecta sobre el producto',
                'Usuario sospechoso con múltiples reportes'
            ]]
        ];

        $createdCount = 0;

        foreach ($listings->take(5) as $listing) {
            $reportType = $reportTypes[array_rand($reportTypes)];
            $reporter = $reporters->where('id', '!=', $listing->user_id)->random();
            
            $report = Report::create([
                'reporter_id' => $reporter->id,
                'reported_user_id' => $listing->user_id,
                'listing_id' => $listing->id,
                'reason' => $reportType['reason'],
                'description' => $reportType['descriptions'][array_rand($reportType['descriptions'])],
                'status' => 'pending',
                'is_anonymous' => rand(0, 1),
            ]);

            $this->line("✅ Reporte creado: {$reportType['reason']} por {$reporter->name} sobre '{$listing->title}'");
            $createdCount++;
        }

        // Crear algunos reportes ya resueltos
        foreach ($listings->skip(5)->take(2) as $listing) {
            $reportType = $reportTypes[array_rand($reportTypes)];
            $reporter = $reporters->where('id', '!=', $listing->user_id)->random();
            
            $report = Report::create([
                'reporter_id' => $reporter->id,
                'reported_user_id' => $listing->user_id,
                'listing_id' => $listing->id,
                'reason' => $reportType['reason'],
                'description' => $reportType['descriptions'][array_rand($reportType['descriptions'])],
                'status' => 'resolved',
                'is_anonymous' => rand(0, 1),
                'resolved_at' => now()->subHours(rand(1, 24)),
                'admin_notes' => 'Reporte revisado - Sin infracción detectada',
            ]);

            $this->line("✅ Reporte resuelto creado: {$reportType['reason']} sobre '{$listing->title}'");
            $createdCount++;
        }

        $this->newLine();
        $this->info("🎉 ¡Listo! Se crearon {$createdCount} reportes de prueba.");
        $this->info("📊 Estados: " . Report::where('status', 'pending')->count() . " pendientes, " . Report::where('status', 'resolved')->count() . " resueltos");

        return 0;
    }
}