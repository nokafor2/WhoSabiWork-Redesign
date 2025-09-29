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
        
        $this->command->info("Found {$photographComments->count()} photograph comments");
        
        $totalRepliesCreated = 0;
        
        // Create replies for 60% of photograph comments
        $commentsToReply = ceil($photographComments->count() * 0.60);
        $selectedComments = $photographComments->random($commentsToReply);
        
        $this->command->info("Creating replies for {$commentsToReply} out of {$photographComments->count()} photograph comments");
        
        $selectedComments->each(function($comment) use (&$totalRepliesCreated) {
            // Create 1-2 replies per selected comment
            $replyCount = rand(1, 2);
            
            for ($i = 0; $i < $replyCount; $i++) {
                \App\Models\PhotographReply::factory()->create([
                    'photograph_id' => $comment->photograph_id,
                    'comment_id' => $comment->id,
                    'user_id' => \App\Models\User::inRandomOrder()->first()->id, // Random user making reply
                    'user_id_reply' => $comment->user_id, // Replying to the comment author
                ]);
                $totalRepliesCreated++;
            }
        });
        
        $this->command->info("Total photograph replies created: {$totalRepliesCreated}");
    }
}
