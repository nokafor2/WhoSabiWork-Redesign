<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        
        // Create replies for 60% of photograph comments
        $commentsToReply = ceil($photographComments->count() * 0.60);
        $selectedComments = $photographComments->random($commentsToReply);
        
        $this->command->info("Creating replies for {$commentsToReply} out of {$photographComments->count()} photograph comments");
        
        $selectedComments->each(function($comment) use (&$totalRepliesCreated, $users) {
            // Create 1-3 replies per selected comment (allowing multiple replies)
            $replyCount = rand(1, 3);
            
            for ($i = 0; $i < $replyCount; $i++) {
                // Get a random user to reply (can be different users replying multiple times)
                $randomUser = $users->random();
                
                \App\Models\PhotographReply::create([
                    'photograph_id' => $comment->photograph_id,
                    'comment_id' => $comment->id,
                    'user_id_comment' => $comment->user_id_comment, // Owner of the photograph comment
                    'user_id_reply' => $randomUser->id, // User replying to the comment
                    'reply' => fake()->sentence(8),
                ]);
                $totalRepliesCreated++;
            }
        });
        
        $this->command->info("Total photograph replies created: {$totalRepliesCreated}");
    }
}
