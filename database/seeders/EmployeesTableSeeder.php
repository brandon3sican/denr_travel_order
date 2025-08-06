<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            $middleName = $nameParts[1] ?? 'User';
            $lastName = $nameParts[2] ?? ($nameParts[1] ?? 'User');
            
            // If only two parts, treat as first and last name
            if (count($nameParts) === 2) {
                $lastName = $nameParts[1];
                $middleName = 'User';
            }

            // Define possible values
            $positions = [
                'Administrative Aide I',
                'Administrative Aide II',
                'Administrative Aide III',
                'Administrative Aide IV',
                'Administrative Assistant I',
                'Administrative Assistant II',
                'Administrative Assistant III',
                'Administrative Assistant IV',
                'Administrative Officer I',
                'Administrative Officer II',
                'Administrative Officer III',
                'Administrative Officer IV',
                'Administrative Officer V',
                'Director I',
                'Director II',
                'Director III',
                'Director IV',
                'Supervising Administrative Officer',
                'Chief Administrative Officer',
                'Planning Officer I',
                'Planning Officer II',
                'Planning Officer III',
                'Planning Officer IV',
                'Planning Officer V',
                'Engineer I',
                'Engineer II',
                'Engineer III',
                'Engineer IV',
                'Engineer V',
                'Forester I',
                'Forester II',
                'Forester III',
                'Forester IV',
                'Forester V',
                'Geologist I',
                'Geologist II',
                'Geologist III',
                'Geologist IV',
                'Geologist V',
                'Biologist I',
                'Biologist II',
                'Biologist III',
                'Biologist IV',
                'Biologist V',
            ];

            $assignments = [
                'DENR - Regional Office',
                'PENRO - Provincial Environment and Natural Resources Office',
                'CENRO - Community Environment and Natural Resources Office',
                'EMB - Environmental Management Bureau',
                'MGB - Mines and Geosciences Bureau',
                'FMB - Forest Management Bureau',
                'BMB - Biodiversity Management Bureau',
                'LMB - Land Management Bureau',
                'ERDB - Ecosystems Research and Development Bureau',
            ];

            $divisions = [
                'Office of the Regional Executive Director',
                'Office of the Assistant Regional Director',
                'Planning and Management Division',
                'Finance and Administrative Division',
                'Technical Services Division',
                'Conservation and Development Division',
                'Licensing, Patents, and Deeds Division',
                'Environmental Management and Protected Areas Division',
                'Mines and Geosciences Division',
                'Forestry Division',
                'Lands and Water Resources Division',
                'Research and Development Division',
                'Legal Division',
                'Internal Audit Service',
                'Human Resource Development Service',
                'Information and Communications Technology Section',
                'Public Affairs Office',
                'Gender and Development Focal Point System',
            ];

            // Create employee record
            Employee::create([
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'sex' => rand(0, 1) ? 'M' : 'F',
                'email' => $user->email,
                'emp_status' => rand(0, 1) ? 'Active' : 'Inactive',
                'position_name' => $positions[array_rand($positions)],
                'assignment_name' => $assignments[array_rand($assignments)],
                'div_sec_unit' => $divisions[array_rand($divisions)],
            ]);
        }
    }
}
