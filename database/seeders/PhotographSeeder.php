<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhotographSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get list of available photoSample images
        $sampleImages = [
            'photoSample.jpg', 'photoSample1.jpg', 'photoSample2.jpg', 'photoSample3.jpg',
            'photoSample4.jpg', 'photoSample5.jpg', 'photoSample6.jpg', 'photoSample7.jpg',
            'photoSample8.jpg', 'photoSample9.jpg', 'photoSample10.jpg', 'photoSample11.jpg',
            'photoSample12.jpg', 'photoSample13.jpg', 'photoSample14.jpg', 'photoSample15.jpg',
            'photoSample16.jpg', 'photoSample17.jpg', 'photoSample18.jpg', 'photoSample19.jpg',
            'photoSample20.jpg', 'photoSamplePNG.png'
        ];

        // Get all users
        $users = \App\Models\User::all();
        
        if ($users->count() === 0) {
            $this->command->info('No users found, creating some test users first...');
            // Create some test users if none exist
            $users = \App\Models\User::factory(10)->create();
            // Make some of them business users
            $users->take(5)->each(function($user) {
                $user->update(['account_type' => 'business']);
            });
        }
        
        $this->command->info("Found {$users->count()} users");
        
        // Get business users
        $businessUsers = $users->where('account_type', 'business');
        $regularUsers = $users->where('account_type', '!=', 'business');
        
        $this->command->info("Business users: {$businessUsers->count()}, Regular users: {$regularUsers->count()}");
        
        $totalPhotographs = 0;
        
        // 1. Create avatars for ALL users
        $this->command->info('Creating avatars for all users...');
        $users->each(function($user) use ($sampleImages, &$totalPhotographs) {
            $timestamp = time() + rand(1, 1000); // Ensure unique timestamps
            $selectedImage = collect($sampleImages)->random();
            $pathInfo = pathinfo($selectedImage);
            $extension = $pathInfo['extension'];
            
            // Naming convention: username_avatar_timestamp
            $filename = ($user->username ?? 'user' . $user->id) . '_avatar_' . $timestamp . '.' . $extension;
            
            // Copy the sample image to storage
            $sourcePath = base_path('resources/Images/' . $selectedImage);
            $destinationPath = 'images/' . $filename;
            
            if (file_exists($sourcePath)) {
                // Copy file using PHP's copy function since it's a local file
                copy($sourcePath, storage_path('app/public/' . $destinationPath));
                
                \App\Models\Photograph::create([
                    'user_id' => $user->id,
                    'filename' => $filename,
                    'path' => $destinationPath,
                    'size' => filesize($sourcePath),
                    'caption' => null, // Avatars don't have captions
                    'photo_type' => 'avatar',
                    'visible' => true,
                    'created_at' => now()->subDays(rand(1, 180)),
                    'updated_at' => now()->subDays(rand(1, 180))
                ]);
                
                $totalPhotographs++;
                $this->command->info("Created avatar for user: " . ($user->username ?? $user->id));
            }
        });
        
        // 2. Create gallery images for business users (3-5 images each)
        $this->command->info('Creating gallery images for business users...');
        $businessUsers->each(function($user) use ($sampleImages, &$totalPhotographs) {
            $galleryCount = rand(3, 5);
            $baseTimestamp = time() + rand(1000, 10000);
            
            for ($i = 0; $i < $galleryCount; $i++) {
                $timestamp = $baseTimestamp + ($i * 100); // Ensure different timestamps
                $selectedImage = collect($sampleImages)->random();
                $pathInfo = pathinfo($selectedImage);
                $extension = $pathInfo['extension'];
                
                // Naming convention: username_timestamp_index
                $filename = ($user->username ?? 'user' . $user->id) . '_' . $timestamp . '_' . $i . '.' . $extension;
                
                // Copy the sample image to storage
                $sourcePath = base_path('resources/Images/' . $selectedImage);
                $destinationPath = 'images/' . $filename;
                
                if (file_exists($sourcePath)) {
                    copy($sourcePath, storage_path('app/public/' . $destinationPath));
                    
                    \App\Models\Photograph::create([
                        'user_id' => $user->id,
                        'filename' => $filename,
                        'path' => $destinationPath,
                        'size' => filesize($sourcePath),
                        'caption' => fake()->optional(0.7)->sentence(),
                        'photo_type' => 'gallery',
                        'visible' => true,
                        'created_at' => now()->subDays(rand(1, 180)),
                        'updated_at' => now()->subDays(rand(1, 180))
                    ]);
                    
                    $totalPhotographs++;
                }
            }
            
            $this->command->info("Created {$galleryCount} gallery images for business user: " . ($user->username ?? $user->id));
        });
        
        // 3. Create ONE cover photo for each business user
        $this->command->info('Creating cover photos for business users...');
        $businessUsers->each(function($user) use ($sampleImages, &$totalPhotographs) {
            $timestamp = time() + rand(20000, 30000);
            $selectedImage = collect($sampleImages)->random();
            $pathInfo = pathinfo($selectedImage);
            $extension = $pathInfo['extension'];
            
            // Naming convention: username_timestamp_cover (using index 'cover' for cover photos)
            $filename = ($user->username ?? 'user' . $user->id) . '_' . $timestamp . '_cover.' . $extension;
            
            // Copy the sample image to storage
            $sourcePath = base_path('resources/Images/' . $selectedImage);
            $destinationPath = 'images/' . $filename;
            
            if (file_exists($sourcePath)) {
                copy($sourcePath, storage_path('app/public/' . $destinationPath));
                
                \App\Models\Photograph::create([
                    'user_id' => $user->id,
                    'filename' => $filename,
                    'path' => $destinationPath,
                    'size' => filesize($sourcePath),
                    'caption' => fake()->optional(0.8)->sentence(),
                    'photo_type' => 'cover photo',
                    'visible' => true,
                    'created_at' => now()->subDays(rand(1, 180)),
                    'updated_at' => now()->subDays(rand(1, 180))
                ]);
                
                $totalPhotographs++;
                $this->command->info("Created cover photo for business user: " . ($user->username ?? $user->id));
            }
        });
        
        $this->command->info("Photograph seeding completed!");
        $this->command->info("Total photographs created: {$totalPhotographs}");
        $this->command->info("- Avatars: {$users->count()}");
        $this->command->info("- Gallery images: " . ($businessUsers->count() * 4) . " (avg 4 per business user)");
        $this->command->info("- Cover photos: {$businessUsers->count()}");
    }
}
