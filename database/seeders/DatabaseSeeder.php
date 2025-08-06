<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\EmployeesTableSeeder;
use Database\Seeders\TravelOrderStatusSeeder;
use Database\Seeders\TravelOrderRoleSeeder;
use Database\Seeders\UserTravelOrderRoleSeeder;
use Database\Seeders\TravelOrderSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Run seeders in correct order
        $this->call([
            UsersTableSeeder::class,
            TravelOrderStatusSeeder::class, // This needs to run before TravelOrderSeeder
            TravelOrderRoleSeeder::class,   // This needs to run before UserTravelOrderRoleSeeder
            EmployeesTableSeeder::class,
            UserTravelOrderRoleSeeder::class,
            TravelOrderSeeder::class,       // This should be one of the last to run
        ]);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
