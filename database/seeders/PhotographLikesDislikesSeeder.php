<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhotographLikesDislikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all photographs
        $photographs = \App\Models\Photograph::all();
        
        if ($photographs->count() === 0) {
            $this->command->info('There are no photographs, so no likes/dislikes will be added');
            return;
        }
        
        // Get all users
        $users = \App\Models\User::all();
        
        if ($users->count() === 0) {
            $this->command->info('There are no users, so no likes/dislikes will be added');
            return;
        }
        
        $this->command->info("Found {$photographs->count()} photographs and {$users->count()} users");
        
        $totalLikesDislikesCreated = 0;
        
        // For each photograph, create likes/dislikes from random users
        $photographs->each(function($photograph) use ($users, &$totalLikesDislikesCreated) {
            // Randomly select 30-70% of users to interact with this photograph
            $interactionPercentage = rand(30, 70);
            $usersToInteract = $users->random(ceil($users->count() * $interactionPercentage / 100));
            
            $this->command->info("Creating likes/dislikes for photograph ID: {$photograph->id} from {$usersToInteract->count()} users");
            
            $usersToInteract->each(function($user) use ($photograph, &$totalLikesDislikesCreated) {
                // 70% chance of like, 30% chance of dislike
                $isLike = rand(1, 100) <= 70;
                
                try {
                    \App\Models\PhotographLikeDislike::create([
                        'photograph_id' => $photograph->id,
                        'user_id' => $user->id,
                        'user_id_like' => $isLike ? $user->id : null,
                        'user_id_dislike' => !$isLike ? $user->id : null,
                    ]);
                    $totalLikesDislikesCreated++;
                } catch (\Exception $e) {
                    // Skip if duplicate (user already liked/disliked this photograph)
                    $this->command->warn("Skipped duplicate like/dislike for user {$user->id} on photograph {$photograph->id}");
                }
            });
        });
        
        $this->command->info("Total photograph likes/dislikes created: {$totalLikesDislikesCreated}");
    }
}
