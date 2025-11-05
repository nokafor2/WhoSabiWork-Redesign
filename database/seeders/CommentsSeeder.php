<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
        
        // Create 5-10 comments for each business user with time gaps
        $businessUsers->each(function($businessUser) {
            $commentCount = rand(5, 10);
            // $this->command->info("Creating {$commentCount} comments for business user: {$businessUser->userFullName()}");
            
            for ($i = 0; $i < $commentCount; $i++) {
                // Create comments spread over the last 3 months with random gaps
                $daysAgo = rand(1, 90); // Random day within last 3 months
                $hoursOffset = rand(0, 23); // Random hour
                $minutesOffset = rand(0, 59); // Random minute
                
                $createdAt = Carbon::now()->subDays($daysAgo)->subHours($hoursOffset)->subMinutes($minutesOffset);
                $updatedAt = $createdAt->copy()->addMinutes(rand(0, 30)); // Updated within 30 minutes of creation
                
                Comment::create([
                    'user_id_comment' => $businessUser->id, // Comments about this business user
                    'user_id' => User::inRandomOrder()->first()->id, // Random users making comments
                    'body' => fake()->text(200),
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);
            }
        });
        
        $totalComments = Comment::count();
        $this->command->info("Total comments created: {$totalComments}");
    }
}
