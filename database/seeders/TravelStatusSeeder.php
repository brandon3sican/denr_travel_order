<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TravelStatusSeeder extends Seeder {
    public function run() {
        DB::table('travel_status')->insert([
            ['name' => 'For Recommendation'],
            ['name' => 'For Approval'],
            ['name' => 'Approved'],
            ['name' => 'Disapproved'],
            ['name' => 'Cancelled'],
            ['name' => 'Completed'],
        ]);
    }
}