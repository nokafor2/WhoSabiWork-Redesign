<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use App\Models\SparePart;
use Illuminate\Database\Seeder;

class SparePartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // The number of spareParts to be created should not be more than the number of spare part sellers in the business_category table        
        // Get all the spareParts in the business category table
        $businessCategorySparePart = BusinessCategory::all()->where('spare_part_seller', '=', 1);
        
        // Get all spare part type columns from fillable array (using actual database column names)
        $excludedColumns = ['id', 'user_id', 'created_at', 'updated_at', 'deleted_at'];
        $sparePartTypes = array_diff(
            (new SparePart())->getFillable(),
            $excludedColumns
        );
        
        $businessCategorySparePart->each(function ($item, $key) use ($sparePartTypes) {
            // Create spare part with all types set to 0 initially
            $sparePartData = ['user_id' => $item->user_id];
            foreach ($sparePartTypes as $type) {
                $sparePartData[$type] = 0;
            }
            
            // Randomly select 1-3 spare part types to set to 1
            $numberOfTypes = rand(1, 3);
            $sparePartTypesArray = array_values($sparePartTypes);
            $selectedTypes = array_rand($sparePartTypesArray, $numberOfTypes);
            
            // If only one type is selected, array_rand returns an integer, not an array
            if (!is_array($selectedTypes)) {
                $selectedTypes = [$selectedTypes];
            }
            
            // Set the selected types to 1
            foreach ($selectedTypes as $index) {
                $selectedType = $sparePartTypesArray[$index];
                $sparePartData[$selectedType] = 1;
            }
            
            // Create or update the spare part
            SparePart::updateOrCreate(
                ['user_id' => $item->user_id],
                $sparePartData
            );
        });
    }
}
