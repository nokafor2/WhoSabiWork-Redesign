<?php

namespace Database\Seeders;

use App\Models\TruckBrand;
use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;

class TruckBrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // The number of truckBrands to be created, should not be more than the number of trucks in the vehicle categories table        
        // Get all the truckBrands in the vehicle_categories table
        $truckBrands = VehicleCategory::all()->where('truck', '=', 1);
        $truckBrands->each(function ($item, $key) {
            $truckBrand = TruckBrand::factory()->make();
            // Override the user_id set from the factory
            $truckBrand->user_id = $item->user_id;
            // Override the business_category set from the factory
            $truckBrand->business_category = $item->business_category;
            $truckBrand->save();
        });
    }
}
