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
        // The number of products to be created should not be more than the number of aritsans in the business_category table        
        // Get all the products in the business category table
        $businessCategoryProduct = BusinessCategory::all()->where('seller', '=', 1);
        $businessCategoryProduct->each(function ($item, $key) {
            $product = Product::factory()->make();
            $product->user_id = $item->user_id;
            $product->save();
        });
    }
}
