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

        $user2 = 'user2@denr.gov.ph';
        User::create([
            'email' => $user2,
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'first_name' => 'User2',
            'middle_name' => 'A2',
            'last_name' => 'System2',
            'suffix' => null,
            'sex' => 'M',
            'email' => $user2,
            'emp_status' => 'Active',
            'position_name' => 'User2',
            'assignment_name' => 'DENR - Regional Office',
            'div_sec_unit' => 'Office of the Regional Executive Director',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $email3 = 'recommender@denr.gov.ph';
        User::create([
            'email' => $email3,
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'first_name' => 'Recommender',
            'middle_name' => 'User',
            'last_name' => 'Account',
            'suffix' => null,
            'sex' => 'M',
            'email' => $email3,
            'emp_status' => 'Active',
            'position_name' => 'Recommender',
            'assignment_name' => 'DENR - Regional Office',
            'div_sec_unit' => 'Office of the Regional Executive Director',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $recommender2 = 'recommender2@denr.gov.ph';
        User::create([
            'email' => $recommender2,
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'first_name' => 'Recommender2',
            'middle_name' => 'User2',
            'last_name' => 'Account2',
            'suffix' => null,
            'sex' => 'M',
            'email' => $recommender2,
            'emp_status' => 'Active',
            'position_name' => 'Recommender2',
            'assignment_name' => 'DENR - Regional Office',
            'div_sec_unit' => 'Office of the Regional Executive Director',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $email4 = 'approver@denr.gov.ph';
        User::create([
            'email' => $email4,
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'first_name' => 'Approver',
            'middle_name' => 'User',
            'last_name' => 'Account',
            'suffix' => null,
            'sex' => 'M',
            'email' => $email4,
            'emp_status' => 'Active',
            'position_name' => 'Approver',
            'assignment_name' => 'DENR - Regional Office',
            'div_sec_unit' => 'Office of the Regional Executive Director',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $approver2 = 'approver2@denr.gov.ph';
        User::create([
            'email' => $approver2,
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'first_name' => 'Approver2',
            'middle_name' => 'User2',
            'last_name' => 'Account2',
            'suffix' => null,
            'sex' => 'M',
            'email' => $approver2,
            'emp_status' => 'Active',
            'position_name' => 'Approver2',
            'assignment_name' => 'DENR - Regional Office',
            'div_sec_unit' => 'Office of the Regional Executive Director',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $email5 = 'both@denr.gov.ph';
        User::create([
            'email' => $email5,
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'first_name' => 'Both',
            'middle_name' => 'User',
            'last_name' => 'Account',
            'suffix' => null,
            'sex' => 'M',
            'email' => $email5,
            'emp_status' => 'Active',
            'position_name' => 'Recommender and Approver',
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
            'user_email' => 'user2@denr.gov.ph',
            'travel_order_role_id' => 2,
        ]);
        UserTravelOrderRole::create([
            'user_email' => 'recommender@denr.gov.ph',
            'travel_order_role_id' => 3,
        ]);
        UserTravelOrderRole::create([
            'user_email' => 'recommender2@denr.gov.ph',
            'travel_order_role_id' => 3,
        ]);
        UserTravelOrderRole::create([
            'user_email' => 'approver@denr.gov.ph',
            'travel_order_role_id' => 4,
        ]);
        UserTravelOrderRole::create([
            'user_email' => 'approver2@denr.gov.ph',
            'travel_order_role_id' => 4,
        ]);
        UserTravelOrderRole::create([
            'user_email' => 'both@denr.gov.ph',
            'travel_order_role_id' => 5,
        ]);
    }
}