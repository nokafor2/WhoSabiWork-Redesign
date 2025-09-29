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
        // Get all photographs
        $photographs = \App\Models\Photograph::all();
        
        if ($photographs->count() === 0) {
            $this->command->info('There are no photographs, so no photograph comments will be added');
            return;
        }
        
        $this->command->info("Found {$photographs->count()} photographs");
        
        $totalCommentsCreated = 0;
        
        // Create 3-8 comments for each photograph
        $photographs->each(function($photograph) use (&$totalCommentsCreated) {
            $commentCount = rand(3, 8);
            
            $this->command->info("Creating {$commentCount} comments for photograph ID: {$photograph->id}");
            
            for ($i = 0; $i < $commentCount; $i++) {
                \App\Models\PhotographComment::factory()->create([
                    'photograph_id' => $photograph->id,
                    'user_id' => \App\Models\User::inRandomOrder()->first()->id, // Random user making comment
                    'user_id_comment' => $photograph->user_id, // Comment about the photo owner
                ]);
                $totalCommentsCreated++;
            }
        });
        
        $this->command->info("Total photograph comments created: {$totalCommentsCreated}");
    }
}
