<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PhotographLikeDislike>
 */
class PhotographLikeDislikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isLike = $this->faker->boolean(60); // 60% chance it's a like, 40% chance it's a dislike
        $user = \App\Models\User::factory();
        
        return [
            'photograph_id' => \App\Models\Photograph::factory(),
            'user_id' => $user,
            'user_id_like' => $isLike ? $user : null,
            'user_id_dislike' => !$isLike ? $user : null,
        ];
    }

    /**
     * Create a like entry
     */
    public function like()
    {
        return $this->state(function (array $attributes) {
            $user = $attributes['user_id'] ?? \App\Models\User::factory();
            return [
                'user_id_like' => $user,
                'user_id_dislike' => null,
            ];
        });
    }

    /**
     * Create a dislike entry
     */
    public function dislike()
    {
        return $this->state(function (array $attributes) {
            $user = $attributes['user_id'] ?? \App\Models\User::factory();
            return [
                'user_id_like' => null,
                'user_id_dislike' => $user,
            ];
        });
    }
}
