<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Get the user_id
        if (Product::all()->last() !== null) {
            $user_id = Product::all()->last()->user_id + 1;
        } else {
            $user_id = 1;
        }

        // Generate the column of the data to be filled
        $productObj = new Product();
        // Get all the products available in an array (NB its in a collection)
        $productArray = $productObj->getTableColumnsWithSort($productObj->table, Product::$columnsToExclude);
        
        // This is an array collection method
        // $productArray->each(function ($item, $key) use($productArray) {
        //     $productArray[$key] = $this->faker->numberBetween(0,1);
        // });
        // $productArray->prepend($user_id, 'user_id');
        // // Convert from collection to PHP array before retuning data
        // return $productArray->toArray();
        
        // Convert to array
        $newProductArray = $productArray->toArray();
        foreach ($newProductArray as $key => $value) {
            $newProductArray[$key] = $this->faker->numberBetween(0,1);
        }
        $newProductArray = array('user_id' => $user_id) + $newProductArray;

        return $newProductArray;
    }
}
