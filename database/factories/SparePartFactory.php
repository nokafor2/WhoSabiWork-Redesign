<?php

namespace Database\Factories;

use App\Models\SparePart;
use Illuminate\Database\Eloquent\Factories\Factory;

class SparePartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SparePart::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Get the user_id
        if (SparePart::all()->last() !== null) {
            $user_id = SparePart::all()->last()->user_id + 1;
        } else {
            $user_id = 1;
        }

        // Generate the column of the data to be filled
        $sparePartObj = new SparePart();
        // Get all the spareParts available in an array (NB its in a collection)
        $sparePartArray = $sparePartObj->getTableColumnsWithSort($sparePartObj->table, SparePart::$columnsToExclude);
        // Convert to array
        $newSparePartArray = $sparePartArray->toArray();
        foreach ($newSparePartArray as $key => $value) {
            $newSparePartArray[$key] = $this->faker->numberBetween(0,1);
        }
        $newSparePartArray = array('user_id' => $user_id) + $newSparePartArray;

        return $newSparePartArray;
    }
}
