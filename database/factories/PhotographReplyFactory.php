<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PhotographReply>
 */
class PhotographReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'photograph_id' => \App\Models\Photograph::factory(),
            'comment_id' => \App\Models\PhotographComment::factory(),
            'user_id' => \App\Models\User::factory(),
            'user_id_reply' => \App\Models\User::factory(),
            'reply' => $this->faker->sentence(8),
        ];
    }
}
