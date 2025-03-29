<?php

namespace Database\Seeders;

use App\Models\UsersFeedback;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UsersFeedback::factory()->count(100)->create();
    }
}
