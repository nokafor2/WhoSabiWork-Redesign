<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PhotographCommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get only gallery and cover photo photographs (exclude avatars)
        $photographs = \App\Models\Photograph::whereIn('photo_type', ['gallery', 'cover photo'])->get();
        
        if ($photographs->count() === 0) {
            $this->command->info('There are no gallery/cover photographs, so no photograph comments will be added');
            return;
        }
        
        // Get all users
        $users = \App\Models\User::all();
        
        if ($users->count() === 0) {
            $this->command->info('There are no users, so no photograph comments will be added');
            return;
        }
        
        $this->command->info("Found {$photographs->count()} gallery/cover photographs and {$users->count()} users");
        
        $totalCommentsCreated = 0;
        
        // Create 3-8 comments for each photograph with time gaps
        $photographs->each(function($photograph) use (&$totalCommentsCreated, $users) {
            $commentCount = rand(3, 8);
            
            // $this->command->info("Creating {$commentCount} comments for photograph ID: {$photograph->id}");
            
            // Get photograph created date to ensure comments are after photograph creation
            $photographCreatedAt = Carbon::parse($photograph->created_at);
            
            for ($i = 0; $i < $commentCount; $i++) {
                // Get a random user to comment (can be different users commenting multiple times)
                $randomUser = $users->random();
                
                // Create comments between photograph creation and now with random gaps
                $daysSincePhoto = Carbon::now()->diffInDays($photographCreatedAt);
                if ($daysSincePhoto > 0) {
                    $daysAfterPhoto = rand(0, min($daysSincePhoto, 60)); // Up to 60 days after photo or current date
                } else {
                    $daysAfterPhoto = 0;
                }
                
                $hoursOffset = rand(0, 23);
                $minutesOffset = rand(0, 59);
                
                $createdAt = $photographCreatedAt->copy()->addDays($daysAfterPhoto)->addHours($hoursOffset)->addMinutes($minutesOffset);
                $updatedAt = $createdAt->copy()->addMinutes(rand(0, 30)); // Updated within 30 minutes
                
                \App\Models\PhotographComment::create([
                    'photograph_id' => $photograph->id,
                    'photograph_user_id' => $photograph->user_id, // Owner of the photograph
                    'user_id_comment' => $randomUser->id, // User who commented on the photograph
                    'comment' => fake()->paragraph(2),
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);
                $totalCommentsCreated++;
            }
        });
        
        $this->command->info("Total photograph comments created: {$totalCommentsCreated}");
    }
}
