<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BusinessCategory;
use Illuminate\Database\Seeder;

class BusinessCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // $usersCount = count(User::all());
        // $businessCategoryCount = min((int)$this->command->ask('How many business category records would you like?', 20), $usersCount);
        // $users = User::all();

        // $businessCategories = BusinessCategory::factory($businessCategoryCount)->make();
        // foreach($businessCategories as $key => $businessCategory) {
        //     $businessCategory->user_id = $users[$key]->id;
        //     $businessCategory->save();
        // }

        // Get all user that has account_type of business
        $businessUsers = User::all()->where('account_type', '=', 'business');
        foreach($businessUsers as $key => $businessUser) {
            // make factory for business user
            $businessCategory = BusinessCategory::factory()->make();
            $businessCategory->user_id = $businessUser->id;
            $businessCategory->save();
        }
    }
}
