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
        UserTravelOrderRole::create([
            'user_email' => 'admin@denr.gov.ph',
            'travel_order_role_id' => 1,
        ]);
        UserTravelOrderRole::create([
            'user_email' => 'admin2@denr.gov.ph',
            'travel_order_role_id' => 1,
        ]);
        UserTravelOrderRole::create([
            'user_email' => 'juan.delacruz@denr.gov.ph',
            'travel_order_role_id' => 2,
        ]);
        UserTravelOrderRole::create([
            'user_email' => 'maria.santos@denr.gov.ph',
            'travel_order_role_id' => 3,
        ]);
        UserTravelOrderRole::create([
            'user_email' => 'jose.reyes@denr.gov.ph',
            'travel_order_role_id' => 4,
        ]);
    }
}
