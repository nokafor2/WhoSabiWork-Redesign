<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photograph>
 */
class PhotographFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get list of photoSample images
        $sampleImages = [
            'photoSample.jpg', 'photoSample1.jpg', 'photoSample2.jpg', 'photoSample3.jpg',
            'photoSample4.jpg', 'photoSample5.jpg', 'photoSample6.jpg', 'photoSample7.jpg',
            'photoSample8.jpg', 'photoSample9.jpg', 'photoSample10.jpg', 'photoSample11.jpg',
            'photoSample12.jpg', 'photoSample13.jpg', 'photoSample14.jpg', 'photoSample15.jpg',
            'photoSample16.jpg', 'photoSample17.jpg', 'photoSample18.jpg', 'photoSample19.jpg',
            'photoSample20.jpg', 'photoSamplePNG.png'
        ];
        
        $selectedImage = $this->faker->randomElement($sampleImages);
        $pathInfo = pathinfo($selectedImage);
        $extension = $pathInfo['extension'];
        
        // Default filename pattern (will be overridden in seeder for specific naming)
        $filename = 'sample_' . time() . '_' . $this->faker->randomNumber(3) . '.' . $extension;
        
        return [
            'user_id' => \App\Models\User::factory(),
            'filename' => $filename,
            'path' => 'images/' . $filename,
            'size' => $this->faker->numberBetween(50000, 2000000), // 50KB to 2MB
            'caption' => $this->faker->optional(0.7)->sentence(), // 70% chance of having a caption
            'photo_type' => 'gallery', // Default photo type
            'visible' => true,
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => function (array $attributes) {
                return $attributes['created_at'];
            }
        ];
    }

    /**
     * Create an avatar photograph
     */
    public function avatar()
    {
        return $this->state(function (array $attributes) {
            return [
                'photo_type' => 'avatar',
                'caption' => null, // Avatars don't have captions
            ];
        });
    }

    /**
     * Create a gallery photograph
     */
    public function gallery()
    {
        return $this->state(function (array $attributes) {
            return [
                'photo_type' => 'gallery',
            ];
        });
    }

    /**
     * Create a cover photo
     */
    public function coverPhoto()
    {
        return $this->state(function (array $attributes) {
            return [
                'photo_type' => 'cover photo',
                'caption' => $this->faker->optional(0.8)->sentence(), // 80% chance for cover photo caption
            ];
        });
    }
}
