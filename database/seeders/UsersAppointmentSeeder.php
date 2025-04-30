<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UsersAppointment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersAppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all user that has account_type of business
        $businessUsers = User::all()->where('account_type', '=', 'business');
        foreach($businessUsers as $key => $businessUser) {
            // make factory for users appointment
            $numberOfAppointments = rand(1, 10);
            $usersAppointment = UsersAppointment::factory()->count($numberOfAppointments)->create();
            $usersAppointment->user_id = $businessUser->id;
        }
    }
}
