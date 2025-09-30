<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateUserRatings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:user-ratings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all user ratings based on their reviews';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating user ratings...');
        
        $users = User::all();
        $updatedCount = 0;
        
        foreach ($users as $user) {
            $oldRating = $user->rating;
            $oldCount = $user->rating_count;
            
            $user->updateRating();
            
            $newRating = $user->rating;
            $newCount = $user->rating_count;
            
            if ($oldRating != $newRating || $oldCount != $newCount) {
                $this->line("User {$user->id} ({$user->name}): {$oldRating} ({$oldCount}) -> {$newRating} ({$newCount})");
                $updatedCount++;
            }
        }
        
        $this->info("✅ Updated {$updatedCount} users successfully!");
        
        return 0;
    }
}
