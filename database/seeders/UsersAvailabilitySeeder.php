<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UsersAvailability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersAvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all user that has account_type of business
        $businessUsers = User::all()->where('account_type', '=', 'business');
        foreach($businessUsers as $key => $businessUser) {
            // make factory for users availability
            $numberofAvailabilities = rand(1, 10);
            $usersAvailability = UsersAvailability::factory()->count($numberofAvailabilities)->create();
            $usersAvailability->user_id = $businessUser->id;
        }
    }
}
