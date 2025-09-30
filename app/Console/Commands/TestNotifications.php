<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\NewMessageReceived;

class TestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notifications {--sender=1} {--receiver=2}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test message notifications between users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $senderId = $this->option('sender');
        $receiverId = $this->option('receiver');

        $sender = User::find($senderId);
        $receiver = User::find($receiverId);

        if (!$sender || !$receiver) {
            $this->error('Sender or receiver not found!');
            return;
        }

        $this->info("Testing notification from {$sender->name} to {$receiver->name}");

        // Find or create conversation
        $conversation = Conversation::where(function($query) use ($sender, $receiver) {
            $query->where('user1_id', $sender->id)->where('user2_id', $receiver->id);
        })->orWhere(function($query) use ($sender, $receiver) {
            $query->where('user1_id', $receiver->id)->where('user2_id', $sender->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user1_id' => $sender->id,
                'user2_id' => $receiver->id,
            ]);
            $this->info("Created new conversation with ID: {$conversation->id}");
        } else {
            $this->info("Using existing conversation with ID: {$conversation->id}");
        }

        // Create test message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'message' => 'Este es un mensaje de prueba para notificaciones - ' . now()->format('H:i:s'),
        ]);

        $this->info("Created message: {$message->message}");

        // Send notification
        $receiver->notify(new NewMessageReceived($message));
        $this->info("Notification sent successfully!");

        // Show notification data
        $notification = $receiver->notifications()->latest()->first();
        if ($notification) {
            $this->info("Notification created with ID: {$notification->id}");
            $this->info("Notification data: " . json_encode($notification->data, JSON_PRETTY_PRINT));
        }

        return 0;
    }
}
