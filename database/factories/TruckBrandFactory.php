<?php

namespace Database\Factories;

use App\Models\TruckBrand;
use Illuminate\Database\Eloquent\Factories\Factory;

class TruckBrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TruckBrand::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Get the user_id
        if (TruckBrand::all()->last() !== null) {
            $user_id = TruckBrand::all()->last()->user_id + 1;
        } else {
            $user_id = 1;
        }
        // Get the business category
        $businessCategory = $this->faker->randomElement($array = array('technical_service', 'spare_part'));

        // Generate the column of the data to be filled
        $truckBrandObj = new TruckBrand();
        // Get all the truckBrands available in an array (NB its in a collection)
        $truckBrandArray = $truckBrandObj->getTableColumnsWithSort($truckBrandObj->table, TruckBrand::$columnsToExclude);
        // Convert to array
        $newTruckBrandArray = $truckBrandArray->toArray();
        foreach ($newTruckBrandArray as $key => $value) {
            $newTruckBrandArray[$key] = $this->faker->numberBetween(0,1);
        }
        $newTruckBrandArray = array('user_id' => $user_id, 'business_category' => $businessCategory) + $newTruckBrandArray;

        return $newTruckBrandArray;
    }
}
