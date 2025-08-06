<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TravelOrderRole;

class TravelOrderRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TravelOrderRole::create([
            'name' => 'User',
            'description' => 'Regular user',
        ]);
        TravelOrderRole::create([
            'name' => 'Recommender',
            'description' => 'Can recommend travel orders',
        ]);
        TravelOrderRole::create([
            'name' => 'Approver',
            'description' => 'Can approve travel orders',
        ]);
        TravelOrderRole::create([
            'name' => 'Recommender and Approver',
            'description' => 'Can recommend and approve travel orders',
        ]);
        TravelOrderRole::create([
            'name' => 'Admin',
            'description' => 'Super account',
        ]);
    }
}
