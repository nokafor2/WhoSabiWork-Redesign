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
        // The number of technicalServices to be created should not be more than the number of aritsans in the business_category table        
        // Get all the technicalServices in the business category table
        $businessCategoryTechnicalService = BusinessCategory::all()->where('technician', '=', 1);
        $businessCategoryTechnicalService->each(function ($item, $key) {
            $technicalService = TechnicalService::factory()->make();
            $technicalService->user_id = $item->user_id;
            $technicalService->save();
        });
    }
}
