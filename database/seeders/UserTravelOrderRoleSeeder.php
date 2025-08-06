<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserTravelOrderRole;

class UserTravelOrderRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create the default 'User' role (ID 5)
        $defaultRole = \App\Models\TravelOrderRole::firstOrCreate(
            ['id' => 5],
            [
                'name' => 'User',
                'description' => 'Regular user with basic permissions'
            ]
        );

        // Define specific role assignments
        $roleAssignments = [
            ['user_email' => 'admin@denr.gov.ph', 'role_id' => 1],
            ['user_email' => 'admin2@denr.gov.ph', 'role_id' => 1],
            ['user_email' => 'juan.delacruz@denr.gov.ph', 'role_id' => 2],
            ['user_email' => 'maria.santos@denr.gov.ph', 'role_id' => 3],
            ['user_email' => 'jose.reyes@denr.gov.ph', 'role_id' => 4],
        ];

        // Apply specific role assignments
        foreach ($roleAssignments as $assignment) {
            UserTravelOrderRole::updateOrCreate(
                ['user_email' => $assignment['user_email']],
                ['travel_order_role_id' => $assignment['role_id']]
            );
        }

        // Ensure all users have at least the default 'User' role
        $usersWithoutRole = \App\Models\User::whereDoesntHave('travelOrderRoles')->get();
        
        foreach ($usersWithoutRole as $user) {
            UserTravelOrderRole::firstOrCreate(
                ['user_email' => $user->email],
                ['travel_order_role_id' => $defaultRole->id]
            );
        }
    }
}
