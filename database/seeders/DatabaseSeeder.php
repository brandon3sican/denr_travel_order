<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\TravelOrderStatusSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TravelOrderStatusSeeder::class,
            // Add other seeders here
        ]);
    }
}
