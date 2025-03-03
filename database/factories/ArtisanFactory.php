<?php

namespace Database\Factories;

use App\Models\Artisan;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtisanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Artisan::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Get the user_id
        if (Artisan::all()->last() !== null) {
            $user_id = Artisan::all()->last()->user_id + 1;
        } else {
            $user_id = 1;
        }

        // Generate the column of the data to be filled
        $artisanObj = new Artisan();
        // Get all the artisans available in an array (NB its in a collection)
        $artisanArray = $artisanObj->getTableColumnsWithSort($artisanObj->table, Artisan::$columnsToExclude);
        // Convert to array
        $newArtisanArray = $artisanArray->toArray();
        foreach ($newArtisanArray as $key => $value) {
            $newArtisanArray[$key] = $this->faker->numberBetween(0,1);
        }
        $newArtisanArray = array('user_id' => $user_id) + $newArtisanArray;

        return $newArtisanArray;
    }
}
