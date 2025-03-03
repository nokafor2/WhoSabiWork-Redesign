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
        // Get the business category
        $businessCategory = $this->faker->randomElement($array = array('technical_service', 'spare_part'));

        // Generate the column of the data to be filled
        $vehicleCategoryObj = new VehicleCategory();
        // Get all the vehicleCategorys available in an array (NB its in a collection)
        $vehicleCategoryArray = $vehicleCategoryObj->getTableColumnsWithSort($vehicleCategoryObj->table, VehicleCategory::$columnsToExclude);
        // Convert to array
        $newVehicleCategoryArray = $vehicleCategoryArray->toArray();
        foreach ($newVehicleCategoryArray as $key => $value) {
            $newVehicleCategoryArray[$key] = $this->faker->numberBetween(0,1);
        }
        $newVehicleCategoryArray = array('user_id' => $user_id, 'business_category' => $businessCategory) + $newVehicleCategoryArray;

        return $newVehicleCategoryArray;
    }
}
