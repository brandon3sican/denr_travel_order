<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Regular users
        $regularUsers = [
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan.delacruz@denr.gov.ph',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@denr.gov.ph',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ],
            [
                'name' => 'Jose Reyes',
                'email' => 'jose.reyes@denr.gov.ph',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ]
        ];

        // Additional admin users
        $adminUsers = [
            [
                'name' => 'Admin Two',
                'email' => 'admin2@denr.gov.ph',    
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ],
            [
                'name' => 'Admin Three',
                'email' => 'admin3@denr.gov.ph',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]
        ];

        // Create users
        foreach ($regularUsers as $user) {
            User::create($user);
        }

        foreach ($adminUsers as $admin) {
            User::create($admin);
        }
    }
}
