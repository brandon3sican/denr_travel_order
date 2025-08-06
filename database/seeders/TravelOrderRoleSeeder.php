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
        $roles = [
            [
                'name' => 'User',
                'description' => 'Regular user'
            ],
            [
                'name' => 'Recommender',
                'description' => 'Can recommend travel orders'
            ],
            [
                'name' => 'Approver',
                'description' => 'Can approve travel orders'
            ],
            [
                'name' => 'Recommender and Approver',
                'description' => 'Can recommend and approve travel orders'
            ],
            [
                'name' => 'Admin',
                'description' => 'Super account'
            ]
        ];

        foreach ($roles as $role) {
            TravelOrderRole::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
