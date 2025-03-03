<?php

namespace Database\Factories;

use App\Models\BusBrand;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusBrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BusBrand::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Get the user_id
        if (BusBrand::all()->last() !== null) {
            $user_id = BusBrand::all()->last()->user_id + 1;
        } else {
            $user_id = 1;
        }
        // Get the business category
        $businessCategory = $this->faker->randomElement($array = array('technical_service', 'spare_part'));

        // Generate the column of the data to be filled
        $busBrandObj = new BusBrand();
        // Get all the busBrands available in an array (NB its in a collection)
        $busBrandArray = $busBrandObj->getTableColumnsWithSort($busBrandObj->table, BusBrand::$columnsToExclude);
        // Convert to array
        $newBusBrandArray = $busBrandArray->toArray();
        foreach ($newBusBrandArray as $key => $value) {
            $newBusBrandArray[$key] = $this->faker->numberBetween(0,1);
        }
        $newBusBrandArray = array('user_id' => $user_id, 'business_category' => $businessCategory) + $newBusBrandArray;

        return $newBusBrandArray;
    }
}
