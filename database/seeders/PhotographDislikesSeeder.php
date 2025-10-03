<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhotographDislikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get only gallery and cover photo photographs (exclude avatars)
        $photographs = \App\Models\Photograph::whereIn('photo_type', ['gallery', 'cover photo'])->get();
        
        if ($photographs->count() === 0) {
            $this->command->info('There are no photographs, so no dislikes will be added');
            return;
        }
        
        // Get all users
        $users = \App\Models\User::all();
        
        if ($users->count() === 0) {
            $this->command->info('There are no users, so no dislikes will be added');
            return;
        }
        
        $this->command->info("Found {$photographs->count()} gallery/cover photographs and {$users->count()} users");
        
        $totalDislikesCreated = 0;
        
        // Create dislikes for 30% of photographs (fewer than likes)
        $photographsToDislike = ceil($photographs->count() * 0.30);
        $selectedPhotographs = $photographs->random($photographsToDislike);
        
        foreach ($selectedPhotographs as $photograph) {
            // Each photograph gets 1-5 random dislikes from different users (fewer than likes)
            $dislikesCount = rand(1, 5);
            $randomUsers = $users->random(min($dislikesCount, $users->count()));
            
            foreach ($randomUsers as $user) {
                // Skip if user is the owner of the photograph (users can't dislike their own photos)
                if ($user->id == $photograph->user_id) {
                    continue;
                }
                
                // Check if dislike already exists to avoid duplicates
                $existingDislike = \App\Models\PhotographDislike::where([
                    'photograph_id' => $photograph->id,
                    'user_id' => $user->id
                ])->first();
                
                // Also check if user already liked this photo to avoid conflict
                $existingLike = \App\Models\PhotographLike::where([
                    'photograph_id' => $photograph->id,
                    'user_id' => $user->id
                ])->first();
                
                if (!$existingDislike && !$existingLike) {
                    // 80% chance of active dislike (1), 20% chance of cancelled dislike (0)
                    $dislikeStatus = rand(1, 100) <= 80 ? 1 : 0;
                    
                    \App\Models\PhotographDislike::create([
                        'photograph_id' => $photograph->id,
                        'photograph_user_id' => $photograph->user_id, // Owner of the photograph
                        'user_id' => $user->id, // User who disliked the photograph
                        'dislike' => $dislikeStatus // 1 = disliked, 0 = cancelled dislike
                    ]);
                    $totalDislikesCreated++;
                }
            }
        }
        
        $this->command->info("Total photograph dislikes created: {$totalDislikesCreated}");
    }
}
