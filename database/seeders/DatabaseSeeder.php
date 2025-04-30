<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        
        // Manually seed a database
        // DB::table('users')->insert([
        //     'first_name' => 'John',
        //     'last_name' => 'Felix',
        //     'gender' => 'male',
        //     'email' => 'johnfelix@yahoo.com',
        //     'username' => 'johnFelix2',
        //     'password' => Hash::make('password'),
        //     'phone_number' => '08057368566',
        //     'account_status' => 'active',
        //     'account_type' => 'business'
        // ]);

        // // Using a state factory to create a default known user for the database
        // $singleUser = User::factory()->defaultUser()->create();

        // // Using the factory method to seed a database
        // $manyUser = User::factory(19)->create();

        // // Merge the $singleUser and $manyUser into one array
        // $users = $manyUser->concat([$singleUser]);

        // Create factory for addresses
        // $addresses = Address::factory(20)->make();
        // foreach($addresses as $key => $address) {
        //     $address->user_id = $users[$key]->id;
        //     $address->save();
        // }
        
        // Create factory for comments
        // $comments = Comment::factory(50)->make()->each(function($comment) use($users) {
        //     $comment->user_id = $users->random()->id;
        //     $comment->business_id = $users->random()->id;
        //     $comment->save();
        // });

        if ($this->command->confirm('Do you want to refresh the database?', true)) {
            $this->command->call('migrate:refresh');
            $this->command->info('Database was refreshed');
        }

        // This is to remove items from the cache before the database is recreated
        Cache::tags(['user'])->flush();

        // Using the individual seeder classes created
        $this->call([
            UsersSeeder::class, 
            CommentsSeeder::class,
            BusinessCategoriesSeeder::class, 
            AddressesSeeder::class,
            ArtisansSeeder::class,
            ProductsSeeder::class,
            TechnicalServicesSeeder::class,
            SparePartsSeeder::class,
            VehicleCategoriesSeeder::class,
            CarBrandsSeeder::class,
            BusBrandsSeeder::class,
            TruckBrandsSeeder::class,
            TagsSeeder::class,
            UserTagSeeder::class,
            UsersFeedbackSeeder::class,
            UsersRatingSeeder::class,
            UsersAvailabilitySeeder::class,
            UsersAppointmentSeeder::class,
        ]);
        
        // $this->call(UsersTableSeeder::class);
        // $this->call(AddressesTableSeeder::class);
        // $this->call(CommentsTableSeeder::class);        
    }
}
