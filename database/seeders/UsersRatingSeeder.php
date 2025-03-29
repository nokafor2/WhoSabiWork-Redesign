<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UsersRating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all user that has account_type of business
        $businessUsers = User::all()->where('account_type', '=', 'business');
        foreach($businessUsers as $key => $businessUser) {
            // make factory for address
            $usersRating = UsersRating::factory()->make();
            $usersRating->user_id = $businessUser->id;
            $usersRating->save();
        }
    }
}
