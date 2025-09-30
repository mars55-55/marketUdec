<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Obtener notificaciones del usuario
     */
    public function index()
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'notifications' => [],
                'unread_count' => 0,
                'error' => 'Usuario no autenticado'
            ], 401);
        }
        
        // El trait Notifiable proporciona el método notifications()
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications->count()
        ]);
    }

    /**
     * Mostrar página de notificaciones
     */
    public function page()
    {
        return view('notifications.index');
    }

    /**
     * Marcar notificación como leída
     */
    public function markAsRead($notificationId)
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }
        
        // El trait Notifiable proporciona el método notifications()
        $notification = $user->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        $user->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Obtener el conteo de notificaciones no leídas
     */
    public function unreadCount()
    {
        $user = Auth::user();
        
        return response()->json([
            'count' => $user->unreadNotifications->count()
        ]);
    }

    /**
     * Eliminar notificación
     */
    public function destroy($notificationId)
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }
        
        // El trait Notifiable proporciona el método notifications()
        $notification = $user->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->delete();
        }

        return response()->json(['success' => true]);
    }
}
