<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\EmployeesTableSeeder;
use Database\Seeders\TravelOrderStatusSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            UsersTableSeeder::class,
            EmployeesTableSeeder::class,
            TravelOrderStatusSeeder::class,
            TravelOrderRoleSeeder::class,
            TravelOrderSeeder::class,
            UserTravelOrderRoleSeeder::class,
        ]);
    }
}
