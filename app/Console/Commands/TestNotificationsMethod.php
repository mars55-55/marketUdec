<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestNotificationsMethod extends Command
{
    protected $signature = 'test:notifications-method';
    protected $description = 'Prueba si el método notifications funciona en el modelo User';

    public function handle()
    {
        $this->info('🔍 Probando método notifications() del modelo User...');
        
        $user = User::first();
        
        if (!$user) {
            $this->error('No hay usuarios en la base de datos');
            return 1;
        }

        $this->info("Usuario encontrado: {$user->name}");
        
        try {
            $this->info('Probando $user->notifications()...');
            $notificationsQuery = $user->notifications();
            $this->info('✅ Método notifications() funciona correctamente');
            $this->info('Clase: ' . get_class($notificationsQuery));
            
            $this->info('Probando $user->notifications()->count()...');
            $count = $user->notifications()->count();
            $this->info("✅ Conteo de notificaciones: {$count}");
            
            $this->info('Probando $user->unreadNotifications...');
            $unreadCount = $user->unreadNotifications->count();
            $this->info("✅ Notificaciones no leídas: {$unreadCount}");
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('Línea: ' . $e->getLine());
            $this->error('Archivo: ' . $e->getFile());
            return 1;
        }

        $this->info('🎉 Todas las pruebas pasaron correctamente');
        return 0;
    }
}