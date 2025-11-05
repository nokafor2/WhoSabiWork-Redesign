<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // The number of products to be created should not be more than the number of sellers in the business_category table        
        // Get all the products in the business category table
        $businessCategoryProduct = BusinessCategory::all()->where('seller', '=', 1);
        
        // Get all product type columns from fillable array (using actual database column names)
        $excludedColumns = ['id', 'user_id', 'created_at', 'updated_at', 'deleted_at'];
        $productTypes = array_diff(
            (new Product())->getFillable(),
            $excludedColumns
        );
        
        $businessCategoryProduct->each(function ($item, $key) use ($productTypes) {
            // Create product with all types set to 0 initially
            $productData = ['user_id' => $item->user_id];
            foreach ($productTypes as $type) {
                $productData[$type] = 0;
            }
            
            // Randomly select 1-3 product types to set to 1
            $numberOfTypes = rand(1, 3);
            $productTypesArray = array_values($productTypes);
            $selectedTypes = array_rand($productTypesArray, $numberOfTypes);
            
            // If only one type is selected, array_rand returns an integer, not an array
            if (!is_array($selectedTypes)) {
                $selectedTypes = [$selectedTypes];
            }
            
            // Set the selected types to 1
            foreach ($selectedTypes as $index) {
                $selectedType = $productTypesArray[$index];
                $productData[$selectedType] = 1;
            }
            
            // Create or update the product
            Product::updateOrCreate(
                ['user_id' => $item->user_id],
                $productData
            );
        });
    }
}
