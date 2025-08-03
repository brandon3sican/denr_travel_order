<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users that don't have an employee record yet
        $usersWithoutEmployees = User::whereNotIn('email', function($query) {
            $query->select('email')->from('employees');
        })->get();

        foreach ($usersWithoutEmployees as $user) {
            // Extract name parts from email
            $nameParts = explode('@', $user->email);
            $name = $nameParts[0];
            $nameParts = array_map('ucfirst', explode('.', $name));
            
            // Set first name, middle name, and last name
            $firstName = $nameParts[0] ?? 'User';
            $middleName = $nameParts[1] ?? null;
            $lastName = $nameParts[2] ?? ($nameParts[1] ?? 'User');
            
            // If only two parts, treat as first and last name
            if (count($nameParts) === 2) {
                $lastName = $nameParts[1];
                $middleName = 'User';
            }

            // Create employee record
            Employee::create([
                'email' => $user->email,
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'position' => $user->is_admin ? 'System Administrator' : 'Staff',
                'department' => 'DENR',
            ]);
        }
    }
}
