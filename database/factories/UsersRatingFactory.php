<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UsersRating;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UsersRating>
 */
class UsersRatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get the array of business users
        $businessUsers = User::where('account_type', '=', 'business')->get();
        // Get the last user in the data base
        $userSize = User::count();
        return [
            'user_id' => $businessUsers->random()->id,
            'rating' => $this->faker->numberBetween(1,5),
            'rated_by_user' => $businessUsers->random()->id,
        ];
    }
}
