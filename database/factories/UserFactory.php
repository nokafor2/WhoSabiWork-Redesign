<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->randomElement($array = array('male', 'female')),
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'username' => $this->faker->username,
            'password' => Hash::make('password'), // password
            'remember_token' => Str::random(10),
            'phone_number' => '080'.$this->faker->randomNumber(8,false),
            'phone_verified_at' => now(),
            'account_status' => 'active',
            'account_type' => $this->faker->randomElement($array = array('regular', 'business')),
            'is_admin' => false,
            'created_at' => $this->faker->dateTimeBetween('-3 months')
        ];
    }

    // Any component that needs to be overridden will be set here
    public function defaultUser()
    {
        return $this->state([
            'first_name' => 'John',
            'last_name' => 'Felix',
            'gender' => 'male',
            'email' => 'nokafor2@gmail.com',
            'username' => 'johnFelix',
            'password' => Hash::make('Password123$'),
            'phone_number' => '08057368560',
            'account_status' => 'active',
            'account_type' => 'business',
            'is_admin' => true,
            'created_at' => $this->faker->dateTimeBetween('-3 months')
        ]);
    }

    // public function configure()
    // {
    //     // Use these functions to determine what will happen after creating user
    //     // Create an assoication with the dependent Address table
    //     return $this->afterMaking(function (User $user) {
    //         // $user->address()->save(Address::factory()->make());
    //         $user->comments()->save(Comment::factory()->make());
    //     })->afterCreating(function (User $user) {
    //         // $user->address()->save(Address::factory()->make());
    //         $user->comments()->save(Comment::factory()->make());
    //     });
    // }
}
