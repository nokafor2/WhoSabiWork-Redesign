<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reply>
 */
class ReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'comment_id' => \App\Models\Comment::factory(),
            'user_id_comment' => \App\Models\User::factory(),
            'user_id_reply' => \App\Models\User::factory(),
            'body' => $this->faker->paragraph(3),
        ];
    }
}
