<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhotographLikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get only gallery and cover photo photographs (exclude avatars)
        $photographs = \App\Models\Photograph::whereIn('photo_type', ['gallery', 'cover photo'])->get();
        
        if ($photographs->count() === 0) {
            $this->command->info('There are no photographs, so no likes will be added');
            return;
        }
        
        // Get all users
        $users = \App\Models\User::all();
        
        if ($users->count() === 0) {
            $this->command->info('There are no users, so no likes will be added');
            return;
        }
        
        $this->command->info("Found {$photographs->count()} gallery/cover photographs and {$users->count()} users");
        
        $totalLikesCreated = 0;
        
        // Create likes for 60% of photographs
        $photographsToLike = ceil($photographs->count() * 0.60);
        $selectedPhotographs = $photographs->random($photographsToLike);
        
        foreach ($selectedPhotographs as $photograph) {
            // Each photograph gets 5-15 random likes from different users
            $likesCount = rand(5, 15);
            $randomUsers = $users->random(min($likesCount, $users->count()));
            
            foreach ($randomUsers as $user) {
                // Skip if user is the owner of the photograph (users can't like their own photos)
                if ($user->id == $photograph->user_id) {
                    continue;
                }
                
                // Check if like already exists to avoid duplicates
                $existingLike = \App\Models\PhotographLike::where([
                    'photograph_id' => $photograph->id,
                    'user_id' => $user->id
                ])->first();
                
                if (!$existingLike) {
                    // 80% chance of active like (1), 20% chance of cancelled like (0)
                    $likeStatus = rand(1, 100) <= 80 ? 1 : 0;
                    
                    \App\Models\PhotographLike::create([
                        'photograph_id' => $photograph->id,
                        'photograph_user_id' => $photograph->user_id, // Owner of the photograph
                        'user_id' => $user->id, // User who liked the photograph
                        'like' => $likeStatus // 1 = liked, 0 = cancelled like
                    ]);
                    $totalLikesCreated++;
                }
            }
        }
        
        $this->command->info("Total photograph likes created: {$totalLikesCreated}");
    }
}
