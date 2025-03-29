<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UsersFeedback>
 */
class UsersFeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $array = ['complain', 'suggestion', 'request', 'other'];
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'message_subject' => $this->faker->randomElement($array),
            'message_content' => $this->faker->realText(),
            'resolved' => $this->faker->numberBetween(0,1),
        ];
    }
}
