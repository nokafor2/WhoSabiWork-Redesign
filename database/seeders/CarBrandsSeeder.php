<?php

namespace Database\Seeders;

use App\Models\CarBrand;
use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;

class CarBrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // The number of carbrands to be created, it should not be more than the number of cars in the vehicle categories table        
        // Get all the carbrands in the vehicle_categories table
        $carBrands = VehicleCategory::all()->where('car', '=', 1);
        $carBrands->each(function ($item, $key) {
            $carBrand = CarBrand::factory()->make();
            // Override the user_id set from the factory
            $carBrand->user_id = $item->user_id;
            // Override the business_category set from the factory
            $carBrand->business_category = $item->business_category;
            $carBrand->save();
        });
    }
}
