<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TravelOrderStatusSeeder extends Seeder {
    public function run() {
        DB::table('travel_order_status')->insert([
            ['name' => 'For Recommendation', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'For Approval', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Approved', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Disapproved', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cancelled', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Completed', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}