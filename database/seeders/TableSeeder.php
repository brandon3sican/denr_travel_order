<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\TravelOrderRole;
use App\Models\TravelOrderStatus;
use App\Models\UserTravelOrderRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Users and Employees
        $email = 'admin@denr.gov.ph';
        User::create([
            'email' => $email,
            'password' => Hash::make('password123'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'first_name' => 'Admin',
            'middle_name' => 'A',
            'last_name' => 'System',
            'suffix' => null,
            'sex' => 'M',
            'email' => $email,
            'emp_status' => 'Active',
            'position_name' => 'Admin',
            'assignment_name' => 'DENR - Regional Office',
            'div_sec_unit' => 'Office of the Regional Executive Director',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $email2 = 'user@denr.gov.ph';
        User::create([
            'email' => $email2,
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'first_name' => 'User1',
            'middle_name' => 'A',
            'last_name' => 'System',
            'suffix' => null,
            'sex' => 'M',
            'email' => $email2,
            'emp_status' => 'Active',
            'position_name' => 'User',
            'assignment_name' => 'DENR - Regional Office',
            'div_sec_unit' => 'Office of the Regional Executive Director',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $email3 = 'supervisor@denr.gov.ph';
        User::create([
            'email' => $email3,
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'first_name' => 'Immediate',
            'middle_name' => 'Bisor',
            'last_name' => 'Supervisor',
            'suffix' => null,
            'sex' => 'M',
            'email' => $email3,
            'emp_status' => 'Active',
            'position_name' => 'Immediate Supervisor',
            'assignment_name' => 'DENR - Regional Office',
            'div_sec_unit' => 'Office of the Regional Executive Director',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $email4 = 'chief@denr.gov.ph';
        User::create([
            'email' => $email4,
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'first_name' => 'Division',
            'middle_name' => 'Chip',
            'last_name' => 'Chief',
            'suffix' => null,
            'sex' => 'M',
            'email' => $email4,
            'emp_status' => 'Active',
            'position_name' => 'Division Chief',
            'assignment_name' => 'DENR - Regional Office',
            'div_sec_unit' => 'Office of the Regional Executive Director',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $email5 = 'director@denr.gov.ph';
        User::create([
            'email' => $email5,
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'first_name' => 'Regional',
            'middle_name' => 'Executive',
            'last_name' => 'Director',
            'suffix' => null,
            'sex' => 'M',
            'email' => $email5,
            'emp_status' => 'Active',
            'position_name' => 'Regional Executive Director',
            'assignment_name' => 'DENR - Regional Office',
            'div_sec_unit' => 'Office of the Regional Executive Director',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //Travel Order Role
        TravelOrderRole::create([
            'name' => 'Admin',
            'description' => 'Super account',
        ]);
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
        
        //Travel Order Status
        DB::table('travel_order_status')->insert([
            ['name' => 'For Recommendation', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'For Approval', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Approved', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Disapproved', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cancelled', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Completed', 'created_at' => now(), 'updated_at' => now()],
        ]);

        //User Travel Order Role
        UserTravelOrderRole::create([
            'user_email' => 'admin@denr.gov.ph',
            'travel_order_role_id' => 1,
        ]);
        UserTravelOrderRole::create([
            'user_email' => 'user@denr.gov.ph',
            'travel_order_role_id' => 2,
        ]);
        UserTravelOrderRole::create([
            'user_email' => 'supervisor@denr.gov.ph',
            'travel_order_role_id' => 3,
        ]);
        UserTravelOrderRole::create([
            'user_email' => 'director@denr.gov.ph',
            'travel_order_role_id' => 4,
        ]);
        UserTravelOrderRole::create([
            'user_email' => 'chief@denr.gov.ph',
            'travel_order_role_id' => 5,
        ]);
    }
}