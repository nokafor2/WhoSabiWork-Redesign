<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PhotographRepliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all photograph comments
        $photographComments = \App\Models\PhotographComment::all();
        
        if ($photographComments->count() === 0) {
            $this->command->info('There are no photograph comments, so no photograph replies will be added');
            return;
        }
        
        // Get all users
        $users = \App\Models\User::all();
        
        if ($users->count() === 0) {
            $this->command->info('There are no users, so no photograph replies will be added');
            return;
        }
        
        $this->command->info("Found {$photographComments->count()} photograph comments and {$users->count()} users");
        
        $totalRepliesCreated = 0;
        
        // Create replies for 60% of photograph comments with time gaps
        $commentsToReply = ceil($photographComments->count() * 0.60);
        $selectedComments = $photographComments->random($commentsToReply);
        
        // $this->command->info("Creating replies for {$commentsToReply} out of {$photographComments->count()} photograph comments");
        
        $selectedComments->each(function($comment) use (&$totalRepliesCreated, $users) {
            // Create 1-3 replies per selected comment (allowing multiple replies)
            $replyCount = rand(1, 3);
            
            // Get comment created date to ensure replies are after comment creation
            $commentCreatedAt = Carbon::parse($comment->created_at);
            
            for ($i = 0; $i < $replyCount; $i++) {
                // Get a random user to reply (can be different users replying multiple times)
                $randomUser = $users->random();
                
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
                
                \App\Models\PhotographReply::create([
                    'photograph_id' => $comment->photograph_id,
                    'comment_id' => $comment->id,
                    'user_id_comment' => $comment->user_id_comment, // Owner of the photograph comment
                    'user_id_reply' => $randomUser->id, // User replying to the comment
                    'reply' => fake()->sentence(8),
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);
                $totalRepliesCreated++;
            }
        });
        
        $this->command->info("Total photograph replies created: {$totalRepliesCreated}");
    }
}
