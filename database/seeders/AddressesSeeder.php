<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // The number of address to be created should not be more than the number of users because they have a one to one relationship
        // So a minimum will be used and it would have to be the size of the users created.
        // $usersCount = count(User::all());
        // $addressCount = min((int)$this->command->ask('How many addresses would you like?', 20), $usersCount);
        // $users = User::all();

        // $addresses = Address::factory($addressCount)->make();
        // foreach($addresses as $key => $address) {
        //     $address->user_id = $users[$key]->id;
        //     $address->save();
        // }

        // Get all user that has account_type of business
        $businessUsers = User::all()->where('account_type', '=', 'business');
        foreach($businessUsers as $key => $businessUser) {
            // make factory for address
            $address = Address::factory()->make();
            $address->user_id = $businessUser->id;
            $address->save();
        }
    }
}
