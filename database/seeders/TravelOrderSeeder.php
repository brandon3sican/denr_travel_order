<?php

namespace Database\Seeders;

use App\Models\TravelOrder;
use App\Models\TravelOrderStatus;
use Illuminate\Database\Seeder;

class TravelOrderSeeder extends Seeder
{
    public function run(): void
    {
        //Travel Order
        // Get the status ID for 'Approved' status
        // Get all statuses
        $statuses = TravelOrderStatus::all()->keyBy('name');
        
        if ($statuses->isEmpty()) {
            throw new \RuntimeException('No travel order statuses found. Make sure to run TableSeeder first.');
        }

        // Travel Order 1: For Recommendation
        TravelOrder::create([
            'employee_email' => 'user@denr.gov.ph',
            'employee_salary' => 25000,
            'destination' => 'Region 1 Office',
            'purpose' => 'Annual Planning Workshop',
            'departure_date' => '2025-08-15',
            'arrival_date' => '2025-08-17',
            'recommender_email' => 'recommender@denr.gov.ph',
            'approver_email' => 'approver@denr.gov.ph',
            'appropriation' => 'Regular Fund',
            'per_diem' => 1500,
            'laborer_assistant' => 2,
            'remarks' => 'To attend the annual planning workshop',
            'status_id' => $statuses['For Recommendation']->id,
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(5),
        ]);

        // Travel Order 2: For Approval
        TravelOrder::create([
            'employee_email' => 'user2@denr.gov.ph',
            'employee_salary' => 45000,
            'destination' => 'Region 2 Office',
            'purpose' => 'Technical Training',
            'departure_date' => '2025-08-20',
            'arrival_date' => '2025-08-22',
            'recommender_email' => 'recommender@denr.gov.ph',
            'approver_email' => 'approver@denr.gov.ph',
            'appropriation' => 'Training Fund',
            'per_diem' => 1200,
            'laborer_assistant' => 1,
            'remarks' => 'To attend technical training on environmental management',
            'status_id' => $statuses['For Approval']->id,
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(2),
        ]);

        // Travel Order 3: Approved
        TravelOrder::create([
            'employee_email' => 'both@denr.gov.ph',
            'employee_salary' => 25000,
            'destination' => 'DENR Central Office',
            'purpose' => 'Budget Hearing',
            'departure_date' => '2025-09-01',
            'arrival_date' => '2025-09-03',
            'recommender_email' => 'recommender@denr.gov.ph',
            'approver_email' => 'approver@denr.gov.ph',
            'appropriation' => 'Regular Fund',
            'per_diem' => 2000,
            'laborer_assistant' => 0,
            'remarks' => 'To attend the budget hearing for next fiscal year',
            'status_id' => $statuses['Approved']->id,
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(8),
        ]);

        // Travel Order 4: Disapproved
        TravelOrder::create([
            'employee_email' => 'user@denr.gov.ph',
            'employee_salary' => 20000,
            'destination' => 'Cebu City',
            'purpose' => 'Seminar on Climate Change',
            'departure_date' => '2025-08-10',
            'arrival_date' => '2025-08-12',
            'recommender_email' => 'recommender@denr.gov.ph',
            'approver_email' => 'approver@denr.gov.ph',
            'appropriation' => 'Training Fund',
            'per_diem' => 1800,
            'laborer_assistant' => 0,
            'remarks' => 'Seminar attendance - Disapproved due to budget constraints',
            'status_id' => $statuses['Disapproved']->id,
            'created_at' => now()->subDays(7),
            'updated_at' => now()->subDays(6),
        ]);

        // Travel Order 5: Cancelled
        TravelOrder::create([
            'employee_email' => 'user2@denr.gov.ph',
            'employee_salary' => 40000,
            'destination' => 'Davao City',
            'purpose' => 'Field Inspection',
            'departure_date' => '2025-07-25',
            'arrival_date' => '2025-07-27',
            'recommender_email' => 'recommender2@denr.gov.ph',
            'approver_email' => 'approver2@denr.gov.ph',
            'appropriation' => 'Field Operations',
            'per_diem' => 1600,
            'laborer_assistant' => 3,
            'remarks' => 'Cancelled due to typhoon warning',
            'status_id' => $statuses['Cancelled']->id,
            'created_at' => now()->subDays(15),
            'updated_at' => now()->subDays(12),
        ]);

        // Travel Order 6: Completed
        TravelOrder::create([
            'employee_email' => 'both@denr.gov.ph',
            'employee_salary' => 50000,
            'destination' => 'Palawan',
            'purpose' => 'Biodiversity Assessment',
            'departure_date' => '2025-07-01',
            'arrival_date' => '2025-07-05',
            'recommender_email' => 'recommender@denr.gov.ph',
            'approver_email' => 'approver@denr.gov.ph',
            'appropriation' => 'Research Fund',
            'per_diem' => 2200,
            'laborer_assistant' => 5,
            'remarks' => 'Successfully completed the biodiversity assessment',
            'status_id' => $statuses['Completed']->id,
            'created_at' => now()->subDays(30),
            'updated_at' => now()->subDays(20),
        ]);
    }
}
