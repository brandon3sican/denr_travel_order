<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TravelOrder;

class TravelOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TravelOrder::create([
            'employee_email' => 'jose.reyes@denr.gov.ph',
            'destination' => 'Destination',
            'purpose' => 'Purpose',
            'departure_date' => '2025-01-01',
            'arrival_date' => '2025-01-02',
            'appropriation' => 'Regular Fund',
            'per_diem' => 1000,
            'laborer_assistant' => 1,
            'remarks' => 'Remarks',
            'status_id' => 1,
        ]);
        TravelOrder::create([
            'employee_email' => 'jose.reyes@denr.gov.ph',
            'destination' => 'Destination2',
            'purpose' => 'Purpose2',
            'departure_date' => '2025-01-01',
            'arrival_date' => '2025-01-02',
            'appropriation' => 'Regular Fund',
            'per_diem' => 1000,
            'laborer_assistant' => 1,
            'remarks' => 'Remarks2',
            'status_id' => 2,
        ]);
        TravelOrder::create([
            'employee_email' => 'juan.delacruz@denr.gov.ph',
            'destination' => 'Destination3',
            'purpose' => 'Purpose3',
            'departure_date' => '2025-01-01',
            'arrival_date' => '2025-01-02',
            'appropriation' => 'Regular Fund',
            'per_diem' => 1000,
            'laborer_assistant' => 1,
            'remarks' => 'Remarks3',
            'status_id' => 1,
        ]);
        TravelOrder::create([
            'employee_email' => 'maria.santos@denr.gov.ph',
            'destination' => 'Destination4',
            'purpose' => 'Purpose4',
            'departure_date' => '2025-01-01',
            'arrival_date' => '2025-01-02',
            'appropriation' => 'Regular Fund',
            'per_diem' => 1000,
            'laborer_assistant' => 1,
            'remarks' => 'Remarks4',
            'status_id' => 1,
        ]);
    }
}
