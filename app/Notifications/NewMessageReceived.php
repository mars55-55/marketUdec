<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use App\Models\Message;
use App\Models\User;
use App\Models\Conversation;

class NewMessageReceived extends Notification
{
    use Queueable;

    protected $message;
    protected $sender;
    protected $conversation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->sender = $message->sender;
        $this->conversation = $message->conversation;
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
            ->subject('💬 Nuevo mensaje de ' . $this->sender->name)
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Has recibido un nuevo mensaje de **' . $this->sender->name . '**')
            ->line('Mensaje: "' . Str::limit($this->message->message, 100) . '"')
            ->action('Ver Conversación', route('conversations.index', ['conversation' => $this->conversation->id]))
            ->line('¡Responde pronto para mantener la conversación activa!')
            ->salutation('Saludos, MarketUdeC');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_message',
            'title' => 'Nuevo mensaje de ' . $this->sender->name,
            'message' => Str::limit($this->message->message, 100),
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'sender_avatar' => $this->sender->avatar,
            'conversation_id' => $this->conversation->id,
            'message_id' => $this->message->id,
            'url' => route('conversations.index', ['conversation' => $this->conversation->id]),
            'created_at' => $this->message->created_at->toISOString(),
        ];
    }
}
