<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tagCount = Tag::all()->count();

        // Check if no tags is available
        if (0 === $tagCount) {
            $this->command->info('No tags found, skipping assigning tags to users.');
            return;
        }

        // // Ask for the minimum amount of tags to assign to a user
        // $howManyMin = (int)$this->command->ask('Minimum tags on users?', 1);
        // $howManyMax = min((int)$this->command->ask('Maximum tags on user?', $tagCount), $tagCount);

        // User::all()->each(function(User $user) use($howManyMin, $howManyMax) {
        //     $take = random_int($howManyMin, $howManyMax);
        //     // the inRandomOrder() method can be used to get record from a table randomly
        //     // Use take() method to get a specified number of data
        //     $tags = Tag::inRandomOrder()->take($take)->get()->pluck('id');
        //     $user->tags()->sync($tags);
        // });

        // Create tags for only business users
        $businessUser = BusinessCategory::all();
        $businessUser->each(function(BusinessCategory $businessCategory) {
            $selectedBusinesses = $businessCategory->userBusinessCategory($businessCategory->user_id);
            $tags = array();
            foreach($selectedBusinesses as $business) {
                $tags[] = $this->getTagId($business);
            }
            User::find($businessCategory->user_id)->tags()->sync($tags);
        });
    }

    public function getTagId($businessName) {
        if ($businessName === 'artisan') {
            return 1;
        } elseif ($businessName === 'seller') {
            return 2;
        } elseif ($businessName === 'technician') {
            return 3;
        } elseif ($businessName === 'spare_part_seller') {
            return 4;
        }
    }
}
