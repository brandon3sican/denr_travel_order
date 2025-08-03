<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => 'admin@denr.gov.ph',
            'password' => Hash::make('password'), // Change this to a secure password
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
    }
}
