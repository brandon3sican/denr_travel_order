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
        $roles = [
            ['user_email' => 'admin@denr.gov.ph', 'role_id' => 1],
            ['user_email' => 'admin2@denr.gov.ph', 'role_id' => 1],
            ['user_email' => 'juan.delacruz@denr.gov.ph', 'role_id' => 2],
            ['user_email' => 'maria.santos@denr.gov.ph', 'role_id' => 3],
            ['user_email' => 'jose.reyes@denr.gov.ph', 'role_id' => 4],
        ];

        foreach ($roles as $role) {
            UserTravelOrderRole::firstOrCreate(
                ['user_email' => $role['user_email']],
                ['travel_order_role_id' => $role['role_id']]
            );
        }
    }
}
