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
        // The number of spareParts to be created should not be more than the number of aritsans in the business_category table        
        // Get all the spareParts in the business category table
        $businessCategorySparePart = BusinessCategory::all()->where('spare_part_seller', '=', 1);
        $businessCategorySparePart->each(function ($item, $key) {
            $sparePart = SparePart::factory()->make();
            $sparePart->user_id = $item->user_id;
            $sparePart->save();
        });
    }
}
