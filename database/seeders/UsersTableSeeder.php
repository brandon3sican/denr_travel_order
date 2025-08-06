<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TravelOrderRole;
use App\Models\UserTravelOrderRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Assign default 'User' role to all users
     */
    private function assignDefaultRoleToAllUsers()
    {
        // Get the default 'User' role (ID 5)
        $userRole = TravelOrderRole::find(5);
        
        if (!$userRole) {
            // If 'User' role doesn't exist, create it
            $userRole = TravelOrderRole::create([
                'name' => 'User',
                'description' => 'Regular user with basic permissions'
            ]);
        }

        // Get all users who don't have any role assigned yet
        $usersWithoutRole = User::whereDoesntHave('travelOrderRoles')->get();

        foreach ($usersWithoutRole as $user) {
            // Assign the default 'User' role
            UserTravelOrderRole::firstOrCreate(
                ['user_email' => $user->email],
                ['travel_order_role_id' => $userRole->id]
            );
        }
    }

    public function run(): void
    {
        // Clear existing users (except the one you're logged in with)
        DB::table('users')->whereNotIn('email', ['admin@denr.gov.ph'])->delete();

        // Define all users
        $users = [
            // Admin users (2)
            [
                'email' => 'admin@denr.gov.ph',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'admin2@denr.gov.ph',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Regular users (3)
            [
                'email' => 'juan.delacruz@denr.gov.ph',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'maria.santos@denr.gov.ph',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'jose.reyes@denr.gov.ph',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'john.wick@denr.gov.ph',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert all users
        User::insert($users);
        
        // Assign default 'User' role to all users
        $this->assignDefaultRoleToAllUsers();
    }
}
