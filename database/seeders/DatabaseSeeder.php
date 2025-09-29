<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if ($this->command->confirm('Do you want to refresh the database?', true)) {
            $this->command->call('migrate:refresh');
            $this->command->info('Database was refreshed');
        }

        // This is to remove items from the cache before the database is recreated
        Cache::tags(['user'])->flush();

        // Using the individual seeder classes created
        $this->call([
            UsersSeeder::class,
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
            CommentsSeeder::class, 
            RepliesSeeder::class,
            PhotographSeeder::class,
            PhotographCommentsSeeder::class,
            PhotographRepliesSeeder::class,
            PhotographLikesDislikesSeeder::class,
        ]);       
    }
}
