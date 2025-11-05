<?php

namespace Database\Seeders;

use App\Models\Artisan;
use App\Models\BusinessCategory;
use Illuminate\Database\Seeder;

class ArtisansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // The number of artisans to be created should not be more than the number of artisans in the business_category table        
        // Get all the artisans in the business category table
        $businessCategoryArtisan = BusinessCategory::all()->where('artisan', '=', 1);
        
        // Get all artisan type columns from fillable array (using actual database column names)
        $excludedColumns = ['id', 'user_id', 'created_at', 'updated_at', 'deleted_at'];
        $artisanTypes = array_diff(
            (new Artisan())->getFillable(),
            $excludedColumns
        );
        
        $businessCategoryArtisan->each(function ($item, $key) use ($artisanTypes) {
            // Create artisan with all types set to 0 initially
            $artisanData = ['user_id' => $item->user_id];
            foreach ($artisanTypes as $type) {
                $artisanData[$type] = 0;
            }
            
            // Randomly select 1-3 artisan types to set to 1
            $numberOfTypes = rand(1, 3);
            $artisanTypesArray = array_values($artisanTypes);
            $selectedTypes = array_rand($artisanTypesArray, $numberOfTypes);
            
            // If only one type is selected, array_rand returns an integer, not an array
            if (!is_array($selectedTypes)) {
                $selectedTypes = [$selectedTypes];
            }
            
            // Set the selected types to 1
            foreach ($selectedTypes as $index) {
                $selectedType = $artisanTypesArray[$index];
                $artisanData[$selectedType] = 1;
            }
            
            // Create or update the artisan
            Artisan::updateOrCreate(
                ['user_id' => $item->user_id],
                $artisanData
            );
        });
    }
}
