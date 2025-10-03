<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        
        // Create 3-8 comments for each photograph
        $photographs->each(function($photograph) use (&$totalCommentsCreated, $users) {
            $commentCount = rand(3, 8);
            
            $this->command->info("Creating {$commentCount} comments for photograph ID: {$photograph->id}");
            
            for ($i = 0; $i < $commentCount; $i++) {
                // Get a random user to comment (can be different users commenting multiple times)
                $randomUser = $users->random();
                
                \App\Models\PhotographComment::create([
                    'photograph_id' => $photograph->id,
                    'photograph_user_id' => $photograph->user_id, // Owner of the photograph
                    'user_id_comment' => $randomUser->id, // User who commented on the photograph
                    'comment' => fake()->paragraph(2),
                ]);
                $totalCommentsCreated++;
            }
        });
        
        $this->command->info("Total photograph comments created: {$totalCommentsCreated}");
    }
}
