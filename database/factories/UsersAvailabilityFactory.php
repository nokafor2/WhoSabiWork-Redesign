<?php

namespace Database\Factories;

use App\Models\UsersAvailability;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UsersAvailability>
 */
class UsersAvailabilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get the user_id
        if (UsersAvailability::all()->last() !== null) {
            $user_id = UsersAvailability::all()->last()->user_id + 1;
        } else {
            $user_id = 1;
        }
        // Get the date available
        $dateAvailable = $this->faker->dateTimeBetween('now', '1 month');

        // Generate the column of the data to be filled
        $usersAvailability = new UsersAvailability();
        // Get all the truckBrands available in an array (NB its in a collection)
        $usersAvailabilityArray = $usersAvailability->getTableColumnsWithSort($usersAvailability->table, UsersAvailability::$columnsToExclude)->toArray();
        // Convert to array
        // $usersAvailabilityArray = $truckBrandArray->toArray();
        foreach ($usersAvailabilityArray as $key => $value) {
            $usersAvailabilityArray[$key] = $this->faker->numberBetween(0,1);
        }
        $usersAvailabilityArray = array('user_id' => $user_id, 'date_available' => $dateAvailable) + $usersAvailabilityArray;

        return $usersAvailabilityArray;
    }
}
