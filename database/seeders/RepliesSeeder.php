<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
        
        // For each business user, get their comments and create replies for 75% of them with time gaps
        $businessUsers->each(function($businessUser) use (&$totalRepliesCreated) {
            // Get comments about this business user
            $comments = \App\Models\Comment::where('user_id_comment', $businessUser->id)->get();
            
            if ($comments->count() === 0) {
                $this->command->info("No comments found for business user: {$businessUser->userFullName()}");
                return;
            }
            
            // Calculate 75% of comments (rounded up)
            $commentsToReply = ceil($comments->count() * 0.75);
            
            // $this->command->info("Creating replies for {$commentsToReply} out of {$comments->count()} comments for business user: {$businessUser->userFullName()}");
            
            // Randomly select 75% of comments to reply to
            $selectedComments = $comments->random($commentsToReply);
            
            $selectedComments->each(function($comment) use ($businessUser, &$totalRepliesCreated) {
                // Create 1-3 replies per selected comment
                $replyCount = rand(1, 3);
                
                // Get comment created date to ensure replies are after comment creation
                $commentCreatedAt = Carbon::parse($comment->created_at);
                
                for ($i = 0; $i < $replyCount; $i++) {
                    // Create replies between comment creation and now with random gaps
                    $daysSinceComment = Carbon::now()->diffInDays($commentCreatedAt);
                    if ($daysSinceComment > 0) {
                        $daysAfterComment = rand(0, min($daysSinceComment, 30)); // Up to 30 days after comment
                    } else {
                        $daysAfterComment = 0;
                    }
                    
                    $hoursOffset = rand(0, 23);
                    $minutesOffset = rand(0, 59);
                    
                    $createdAt = $commentCreatedAt->copy()->addDays($daysAfterComment)->addHours($hoursOffset)->addMinutes($minutesOffset);
                    $updatedAt = $createdAt->copy()->addMinutes(rand(0, 20)); // Updated within 20 minutes
                    
                    \App\Models\Reply::create([
                        'comment_id' => $comment->id,
                        'user_id_comment' => $businessUser->id, // Business user being discussed
                        'user_id_reply' => \App\Models\User::inRandomOrder()->first()->id, // Random user making the reply
                        'body' => fake()->paragraph(3),
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                    ]);
                    $totalRepliesCreated++;
                }
            });
        });
        
        $this->command->info("Total replies created: {$totalRepliesCreated}");
    }
}
