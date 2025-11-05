<?php

namespace Database\Seeders;

use App\Models\BusBrand;
use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BusBrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all vehicle categories where bus = 1
        $vehicleCategories = VehicleCategory::where('bus', '=', 1)->get();
        
        // Get all bus brand columns from database (excluding common columns)
        $excludedColumns = ['id', 'user_id', 'business_category', 'created_at', 'updated_at', 'deleted_at'];
        $allColumns = Schema::getColumnListing('bus_brands');
        $busBrandTypesArray = array_diff($allColumns, $excludedColumns);
        
        $vehicleCategories->each(function ($item) use ($busBrandTypesArray) {
            // Initialize all brand types to 0
            $busBrandData = ['user_id' => $item->user_id, 'business_category' => $item->business_category];
            foreach ($busBrandTypesArray as $brandType) {
                $busBrandData[$brandType] = 0;
            }
            
            // Randomly select 1-5 bus brand types to set to 1
            // $numberOfBrands = rand(1, min(5, count($busBrandTypesArray)));
            $numberOfBrands = rand(1, 5);
            $selectedBrands = array_rand(array_flip($busBrandTypesArray), $numberOfBrands);
            
            // If only one brand is selected, array_rand returns an integer, not an array
            if (!is_array($selectedBrands)) {
                $selectedBrands = [$selectedBrands];
            }
            
            // Set the selected brands to 1
            foreach ($selectedBrands as $selectedBrand) {
                $busBrandData[$selectedBrand] = 1;
            }
            
            // Create or update the bus brand record
            BusBrand::updateOrCreate(
                [
                    'user_id' => $item->user_id,
                    'business_category' => $item->business_category
                ],
                $busBrandData
            );
        });
    }
}
