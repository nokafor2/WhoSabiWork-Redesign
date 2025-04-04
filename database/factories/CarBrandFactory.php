<?php

namespace Database\Factories;

use App\Models\CarBrand;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarBrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CarBrand::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Get the user_id
        if (CarBrand::all()->last() !== null) {
            $user_id = CarBrand::all()->last()->user_id + 1;
        } else {
            $user_id = 1;
        }
        // Get the business category
        $businessCategory = $this->faker->randomElement($array = array('technical_service', 'spare_part'));

        // Generate the column of the data to be filled
        $carBrandObj = new CarBrand();
        // Get all the carBrands available in an array (NB its in a collection)
        $carBrandArray = $carBrandObj->getTableColumnsWithSort($carBrandObj->table, CarBrand::$columnsToExclude);
        // Convert to array
        $newCarBrandArray = $carBrandArray->toArray();
        foreach ($newCarBrandArray as $key => $value) {
            $newCarBrandArray[$key] = $this->faker->numberBetween(0,1);
        }
        $newCarBrandArray = array('user_id' => $user_id, 'business_category' => $businessCategory) + $newCarBrandArray;

        return $newCarBrandArray;
    }
}
