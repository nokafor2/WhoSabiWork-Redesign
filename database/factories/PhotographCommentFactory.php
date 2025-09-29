<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PhotographComment>
 */
class PhotographCommentFactory extends Factory
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
            'user_id' => \App\Models\User::factory(),
            'user_id_comment' => \App\Models\User::factory(),
            'comment' => $this->faker->paragraph(2),
        ];
    }
}
