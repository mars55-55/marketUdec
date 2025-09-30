<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Conversation;

class TestBlocking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:blocking {conversation=1} {user=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test conversation blocking functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $conversationId = $this->argument('conversation');
        $userId = $this->argument('user');

        $conversation = Conversation::find($conversationId);

        if (!$conversation) {
            $this->error("Conversation {$conversationId} not found!");
            return;
        }

        $this->info("Testing blocking for conversation {$conversationId} with user {$userId}");
        
        // Test initial state
        $this->info("Initial state - Is blocked by user {$userId}: " . ($conversation->isBlockedBy($userId) ? 'YES' : 'NO'));
        
        // Block user
        $conversation->blockUser($userId);
        $this->info("After blocking - Is blocked by user {$userId}: " . ($conversation->isBlockedBy($userId) ? 'YES' : 'NO'));
        
        // Show blocked_by field
        $this->info("Blocked by field: " . json_encode($conversation->blocked_by));
        
        // Unblock user
        $conversation->unblockUser($userId);
        $this->info("After unblocking - Is blocked by user {$userId}: " . ($conversation->isBlockedBy($userId) ? 'YES' : 'NO'));
        
        // Show blocked_by field again
        $this->info("Blocked by field after unblock: " . json_encode($conversation->blocked_by));

        return 0;
    }
}
