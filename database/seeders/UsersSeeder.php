<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ask the users how many seeders to be created
        // The input required by the factory class is an integer and the 
        // type returned from the command is a string, so it has to be cast to an integer.
        // The max is used as a default for the maximum number that will be created in a situation
        // where the user puts zero
        $userCount = max((int)$this->command->ask('How many users would you like?', 200), 1);
        
        // Using a state factory to create a default known user for the database
        User::factory()->defaultUser()->create();

        // Using the factory method to seed a database
        User::factory($userCount)->create();
        
    }
}
