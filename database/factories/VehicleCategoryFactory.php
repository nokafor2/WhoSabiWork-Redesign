<?php

namespace Database\Factories;

use App\Models\VehicleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VehicleCategory::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Get the user_id
        if (VehicleCategory::all()->last() !== null) {
            $user_id = VehicleCategory::all()->last()->user_id + 1;
        } else {
            $user_id = 1;
        }
        // Get the business category (will be overridden by seeder)
        $businessCategory = $this->faker->randomElement($array = array('technical_service', 'spare_part'));

        // Randomly assign vehicle types (car, bus, truck) with 0 or 1
        // Each vehicle type has a random chance to be assigned
        return [
            'user_id' => $user_id,
            'business_category' => $businessCategory,
            'car' => $this->faker->numberBetween(0, 1),
            'bus' => $this->faker->numberBetween(0, 1),
            'truck' => $this->faker->numberBetween(0, 1),
        ];
    }
}
