<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PhotographDislike>
 */
class PhotographDislikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $photograph = \App\Models\Photograph::factory();
        $dislikerUser = \App\Models\User::factory();
        
        return [
            'photograph_id' => $photograph,
            'photograph_user_id' => function (array $attributes) {
                // Get the photograph owner's user_id
                return \App\Models\Photograph::find($attributes['photograph_id'])->user_id;
            },
            'user_id' => $dislikerUser, // User who disliked the photograph
            'dislike' => $this->faker->boolean(80) ? 1 : 0, // 80% chance of active dislike, 20% cancelled
        ];
    }
}
