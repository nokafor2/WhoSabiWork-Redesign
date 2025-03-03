<?php

namespace Database\Seeders;

use App\Models\SparePart;
use App\Models\TechnicalService;
use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;

class VehicleCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // The number of vehicleCategories to be created should not be more than the number of aritsans in the technical service and spare_part_seller table        
        // Get all the vehicleCategories in the technical service table
        $technicalServiceVehicleCategory = TechnicalService::all();
        $technicalServiceVehicleCategory->each(function ($item, $key) {
            $vehicleCategory = VehicleCategory::factory()->make();
            // Override the user_id set from the factory
            $vehicleCategory->user_id = $item->user_id;
            // Override the business_category set from the factory
            $vehicleCategory->business_category = 'technical_service';
            $vehicleCategory->save();
        });

        // Get all the vehicleCategories in the spare part table
        $sparePartVehicleCategory = SparePart::all();
        $sparePartVehicleCategory->each(function ($item, $key) {
            $vehicleCategory = VehicleCategory::factory()->make();
            // Override the user_id set from the factory
            $vehicleCategory->user_id = $item->user_id;
            // Override the business_category set from the factory
            $vehicleCategory->business_category = 'spare_part';
            $vehicleCategory->save();
        });
    }
}
