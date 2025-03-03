<?php

namespace Database\Factories;

use App\Models\TechnicalService;
use Illuminate\Database\Eloquent\Factories\Factory;

class TechnicalServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TechnicalService::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Get the user_id
        if (TechnicalService::all()->last() !== null) {
            $user_id = TechnicalService::all()->last()->user_id + 1;
        } else {
            $user_id = 1;
        }

        // Generate the column of the data to be filled
        $technicalServiceObj = new TechnicalService();
        // Get all the technicalServices available in an array (NB its in a collection)
        $technicalServiceArray = $technicalServiceObj->getTableColumnsWithSort($technicalServiceObj->table, TechnicalService::$columnsToExclude);
        // Convert to array
        $newTechnicalServiceArray = $technicalServiceArray->toArray();
        foreach ($newTechnicalServiceArray as $key => $value) {
            $newTechnicalServiceArray[$key] = $this->faker->numberBetween(0,1);
        }
        $newTechnicalServiceArray = array('user_id' => $user_id) + $newTechnicalServiceArray;

        return $newTechnicalServiceArray;
    }
}
