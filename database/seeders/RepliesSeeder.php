<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RepliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users with business profiles
        $businessUsers = \App\Models\User::whereHas('businessCategory')->get();
        
        if ($businessUsers->count() === 0) {
            $this->command->info('There are no users with business profiles, so no replies will be added');
            return;
        }
        
        $this->command->info("Found {$businessUsers->count()} users with business profiles");
        
        $totalRepliesCreated = 0;
        
        // For each business user, get their comments and create replies for 75% of them
        $businessUsers->each(function($businessUser) use (&$totalRepliesCreated) {
            // Get comments about this business user
            $comments = \App\Models\Comment::where('user_id_comment', $businessUser->id)->get();
            
            if ($comments->count() === 0) {
                $this->command->info("No comments found for business user: {$businessUser->userFullName()}");
                return;
            }
            
            // Calculate 75% of comments (rounded up)
            $commentsToReply = ceil($comments->count() * 0.75);
            
            $this->command->info("Creating replies for {$commentsToReply} out of {$comments->count()} comments for business user: {$businessUser->userFullName()}");
            
            // Randomly select 75% of comments to reply to
            $selectedComments = $comments->random($commentsToReply);
            
            $selectedComments->each(function($comment) use ($businessUser, &$totalRepliesCreated) {
                // Create 1-3 replies per selected comment
                $replyCount = rand(1, 3);
                
                for ($i = 0; $i < $replyCount; $i++) {
                    \App\Models\Reply::factory()->create([
                        'comment_id' => $comment->id,
                        'user_id_comment' => $businessUser->id, // Business user being discussed
                        'user_id_reply' => \App\Models\User::inRandomOrder()->first()->id, // Random user making the reply
                    ]);
                    $totalRepliesCreated++;
                }
            });
        });
        
        $this->command->info("Total replies created: {$totalRepliesCreated}");
    }
}
