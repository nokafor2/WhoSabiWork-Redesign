<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Get the user_id
        if (Comment::all()->last() !== null) {
            $user_id = Comment::all()->last()->user_id + 1;
        } else {
            $user_id = 1;
        }
        // Get the array of business users
        $businessUsers = User::where('account_type', '=', 'business')->get();
         
        return [
            // 'user_id' => 4,
            'business_id' => $businessUsers->random()->id,
            'body' => $this->faker->text,
            'created_at' => $this->faker->dateTimeBetween('-3 months')
        ];
    }

    // This is useful for test
    public function defaultComment()
    {
        // Get the array of business users
        $businessUsers = User::where('account_type', '=', 'business')->get();
         
        return $this->state([
            // 'user_id' => 4,
            'business_id' => $businessUsers->random()->id,
            'body' => 'Adding new comment.',
            'created_at' => $this->faker->dateTimeBetween('-3 months')
        ]);
    }
}
