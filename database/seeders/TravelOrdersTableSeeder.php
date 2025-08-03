<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TravelOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all statuses
        $statuses = DB::table('travel_order_status')->get()->keyBy('name');
        
        // Get some employees
        $employees = Employee::with('user')->take(5)->get();
        
        if ($employees->isEmpty()) {
            $this->command->info('No employees found. Please run EmployeesTableSeeder first.');
            return;
        }

        $travelOrders = [];
        
        // Generate travel orders for each employee
        foreach ($employees as $employee) {
            // Create 1-3 travel orders per employee
            $numOrders = rand(1, 3);
            
            for ($i = 0; $i < $numOrders; $i++) {
                $departureDate = Carbon::now()->addDays(rand(1, 30));
                $arrivalDate = (clone $departureDate)->addDays(rand(1, 7));
                
                $status = $statuses->random();
                
                $travelOrders[] = [
                    'employee_email' => $employee->email,
                    'destination' => $this->getRandomDestination(),
                    'purpose' => $this->getRandomPurpose(),
                    'departure_date' => $departureDate->format('Y-m-d'),
                    'arrival_date' => $arrivalDate->format('Y-m-d'),
                    'appropriation' => 'Regular Fund',
                    'per_diem' => rand(1000, 5000),
                    'laborer_assistant' => rand(0, 10),
                    'remarks' => $this->getRandomRemarks(),
                    'status_id' => $status->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        // Insert all travel orders
        DB::table('travel_orders')->insert($travelOrders);
    }
    
    /**
     * Get a random destination
     */
    private function getRandomDestination(): string
    {
        $destinations = [
            'Manila', 'Cebu City', 'Davao City', 'Cagayan de Oro', 'Bacolod',
            'Iloilo City', 'Baguio City', 'Dagupan', 'Tuguegarao', 'Vigan',
            'Legazpi', 'Naga', 'Lucena', 'Puerto Princesa', 'Zamboanga City'
        ];
        
        return $destinations[array_rand($destinations)] . ', Philippines';
    }
    
    /**
     * Get a random purpose
     */
    private function getRandomPurpose(): string
    {
        $purposes = [
            'Attend training on environmental conservation',
            'Participate in regional DENR meeting',
            'Conduct field inspection',
            'Attend workshop on forest management',
            'Monitor mining activities',
            'Inspect reforestation projects',
            'Attend climate change summit',
            'Conduct environmental assessment',
            'Participate in tree planting activity',
            'Attend biodiversity forum'
        ];
        
        return $purposes[array_rand($purposes)];
    }
    
    /**
     * Get random remarks
     */
    private function getRandomRemarks(): string
    {
        $remarks = [
            'Urgent travel required',
            'Needs special clearance',
            'With vehicle request',
            'Accommodation arranged',
            'Needs travel advance',
            'Special assignment from Regional Director',
            'Part of inter-agency task force',
            'With official vehicle',
            'Needs additional documents',
            'High priority mission',
            'Standard travel request',
            'For official business',
            'Department meeting attendance',
            'Field work required',
            'Training session attendance'
        ];
        
        return $remarks[array_rand($remarks)];
    }
}
