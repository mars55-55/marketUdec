<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Listing;

class ListingBlockedNotification extends Notification
{
    use Queueable;

    public $listing;
    public $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Listing $listing, string $reason = null)
    {
        $this->listing = $listing;
        $this->reason = $reason ?? 'El anuncio ha sido reportado y bloqueado por el equipo de moderación';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('🚫 Tu anuncio ha sido bloqueado - MarketUdeC')
                    ->greeting('Hola ' . $notifiable->name)
                    ->line('Tu anuncio "' . $this->listing->title . '" ha sido bloqueado.')
                    ->line('**Motivo:** ' . $this->reason)
                    ->line('Si crees que esto es un error, puedes contactar al equipo de moderación.')
                    ->action('Ver mis anuncios', url('/dashboard/listings'))
                    ->line('¡Gracias por usar MarketUdeC!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'listing_blocked',
            'title' => '🚫 Anuncio Bloqueado',
            'message' => 'Tu anuncio "' . $this->listing->title . '" ha sido bloqueado.',
            'listing_id' => $this->listing->id,
            'listing_title' => $this->listing->title,
            'reason' => $this->reason,
            'action_url' => route('listings.show', $this->listing->id),
            'action_text' => 'Ver anuncio',
            'created_at' => now(),
        ];
    }
}