<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        if (Address::all()->last() !== null) {
            $user_id = Address::all()->last()->user_id + 1;
        } else {
            $user_id = 1;
        }
        return [
            // 'address_line_1' => $this->faker->address,
            // 'user_id' => count(Address::all()) + 1,
            'user_id' => $user_id,
            'address_line_1' => $this->faker->streetAddress,
            'address_line_2' => $this->faker->secondaryAddress,
            'town' => $this->faker->city,
            'state' => $this->faker->state,
            'created_at' => $this->faker->dateTimeBetween('-3 months')
        ];
    }

    public function defaultAddress()
    {
        return $this->state([
            // 'user_id' => count(Address::all()) + 1,
            'user_id' => count(Address::all()) + 1,
            'address_line_1' => 'No.5 Ayetoro Street',
            'address_line_2' => 'After Roundabout',
            'town' => 'Epe',
            'state' => 'Lagos',
            'created_at' => $this->faker->dateTimeBetween('-3 months')
        ]);
    }
}
