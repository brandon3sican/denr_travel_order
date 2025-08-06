<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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

        // Insert users
        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
