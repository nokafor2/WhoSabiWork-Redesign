<?php

namespace Database\Seeders;

use App\Models\TruckBrand;
use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TruckBrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all vehicle categories where truck = 1
        $vehicleCategories = VehicleCategory::where('truck', '=', 1)->get();
        
        // Get all truck brand columns from database (excluding common columns)
        $excludedColumns = ['id', 'user_id', 'business_category', 'created_at', 'updated_at', 'deleted_at'];
        $allColumns = Schema::getColumnListing('truck_brands');
        $truckBrandTypesArray = array_diff($allColumns, $excludedColumns);
        
        $vehicleCategories->each(function ($item) use ($truckBrandTypesArray) {
            // Initialize all brand types to 0
            $truckBrandData = ['user_id' => $item->user_id, 'business_category' => $item->business_category];
            foreach ($truckBrandTypesArray as $brandType) {
                $truckBrandData[$brandType] = 0;
            }
            
            // Randomly select 1-5 truck brand types to set to 1
            // $numberOfBrands = rand(1, min(5, count($truckBrandTypesArray)));
            $numberOfBrands = rand(1, 5);
            $selectedBrands = array_rand(array_flip($truckBrandTypesArray), $numberOfBrands);
            
            // If only one brand is selected, array_rand returns an integer, not an array
            if (!is_array($selectedBrands)) {
                $selectedBrands = [$selectedBrands];
            }
            
            // Set the selected brands to 1
            foreach ($selectedBrands as $selectedBrand) {
                $truckBrandData[$selectedBrand] = 1;
            }
            
            // Create or update the truck brand record
            TruckBrand::updateOrCreate(
                [
                    'user_id' => $item->user_id,
                    'business_category' => $item->business_category
                ],
                $truckBrandData
            );
        });
    }
}
