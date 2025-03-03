<?php

namespace Database\Seeders;

use App\Models\BusBrand;
use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;

class BusBrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // The number of busBrands to be created, it should not be more than the number of buses in the vehicle categories table        
        // Get all the busBrands in the vehicle_categories table
        $busBrands = VehicleCategory::all()->where('bus', '=', 1);
        $busBrands->each(function ($item, $key) {
            $busBrand = BusBrand::factory()->make();
            // Override the user_id set from the factory
            $busBrand->user_id = $item->user_id;
            // Override the business_category set from the factory
            $busBrand->business_category = $item->business_category;
            $busBrand->save();
        });
    }
}
