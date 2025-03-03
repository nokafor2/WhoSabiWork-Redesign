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
        // The number of artisans to be created should not be more than the number of aritsans in the business_category table        
        // Get all the artisans in the business category table
        $businessCategoryArtisan = BusinessCategory::all()->where('artisan', '=', 1);
        $businessCategoryArtisan->each(function ($item, $key) {
            $artisan = Artisan::factory()->make();
            $artisan->user_id = $item->user_id;
            $artisan->save();
        });
    }
}
