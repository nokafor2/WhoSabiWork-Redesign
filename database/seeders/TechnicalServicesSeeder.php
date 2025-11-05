<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use App\Models\TechnicalService;
use Illuminate\Database\Seeder;

class TechnicalServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // The number of technicalServices to be created should not be more than the number of technicians in the business_category table        
        // Get all the technicalServices in the business category table
        $businessCategoryTechnicalService = BusinessCategory::all()->where('technician', '=', 1);
        
        // Get all technical service type columns from fillable array (using actual database column names)
        $excludedColumns = ['id', 'user_id', 'created_at', 'updated_at', 'deleted_at'];
        $technicalServiceTypes = array_diff(
            (new TechnicalService())->getFillable(),
            $excludedColumns
        );
        
        $businessCategoryTechnicalService->each(function ($item, $key) use ($technicalServiceTypes) {
            // Create technical service with all types set to 0 initially
            $technicalServiceData = ['user_id' => $item->user_id];
            foreach ($technicalServiceTypes as $type) {
                $technicalServiceData[$type] = 0;
            }
            
            // Randomly select 1-3 technical service types to set to 1
            $numberOfTypes = rand(1, 3);
            $technicalServiceTypesArray = array_values($technicalServiceTypes);
            $selectedTypes = array_rand($technicalServiceTypesArray, $numberOfTypes);
            
            // If only one type is selected, array_rand returns an integer, not an array
            if (!is_array($selectedTypes)) {
                $selectedTypes = [$selectedTypes];
            }
            
            // Set the selected types to 1
            foreach ($selectedTypes as $index) {
                $selectedType = $technicalServiceTypesArray[$index];
                $technicalServiceData[$selectedType] = 1;
            }
            
            // Create or update the technical service
            TechnicalService::updateOrCreate(
                ['user_id' => $item->user_id],
                $technicalServiceData
            );
        });
    }
}
