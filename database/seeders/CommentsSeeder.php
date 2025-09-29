<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get users with business profiles (users who have business categories)
        $businessUsers = User::all()->where('account_type', '=', 'business');
        
        if ($businessUsers->count() === 0) {
            $this->command->info('There are no users with business profiles, so no comments will be added');
            return;
        }
        
        $this->command->info("Found {$businessUsers->count()} users with business profiles");
        
        // Create 5-10 comments for each business user
        $businessUsers->each(function($businessUser) {
            $commentCount = rand(5, 10);
            $this->command->info("Creating {$commentCount} comments for business user: {$businessUser->userFullName()}");
            
            Comment::factory($commentCount)->create([
                'user_id_comment' => $businessUser->id, // Comments about this business user
                'user_id' => User::inRandomOrder()->first()->id, // Random users making comments
            ]);
        });
        
        $totalComments = Comment::count();
        $this->command->info("Total comments created: {$totalComments}");
    }
}
