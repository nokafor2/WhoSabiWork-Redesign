<?php

namespace Database\Seeders;

use App\Models\CarBrand;
use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CarBrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all vehicle categories where car = 1
        $vehicleCategories = VehicleCategory::where('car', '=', 1)->get();
        
        // Get all car brand columns from database (excluding common columns)
        $excludedColumns = ['id', 'user_id', 'business_category', 'created_at', 'updated_at', 'deleted_at'];
        $allColumns = Schema::getColumnListing('car_brands');
        $carBrandTypesArray = array_diff($allColumns, $excludedColumns);
        
        $vehicleCategories->each(function ($item) use ($carBrandTypesArray) {
            // Initialize all brand types to 0
            $carBrandData = ['user_id' => $item->user_id, 'business_category' => $item->business_category];
            foreach ($carBrandTypesArray as $brandType) {
                $carBrandData[$brandType] = 0;
            }
            
            // Randomly select 1-5 car brand types to set to 1
            // $numberOfBrands = rand(1, min(5, count($carBrandTypesArray)));
            $numberOfBrands = rand(1, 5);
            $selectedBrands = array_rand(array_flip($carBrandTypesArray), $numberOfBrands);
            
            // If only one brand is selected, array_rand returns an integer, not an array
            if (!is_array($selectedBrands)) {
                $selectedBrands = [$selectedBrands];
            }
            
            // Set the selected brands to 1
            foreach ($selectedBrands as $selectedBrand) {
                $carBrandData[$selectedBrand] = 1;
            }
            
            // Create or update the car brand record
            CarBrand::updateOrCreate(
                [
                    'user_id' => $item->user_id,
                    'business_category' => $item->business_category
                ],
                $carBrandData
            );
        });
    }
}
