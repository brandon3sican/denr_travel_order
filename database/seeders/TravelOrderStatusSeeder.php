<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TravelOrderStatusSeeder extends Seeder {
    public function run() {
        $statuses = [
            'For Recommendation',
            'For Approval',
            'Approved',
            'Disapproved',
            'Cancelled',
            'Completed'
        ];

        foreach ($statuses as $status) {
            DB::table('travel_order_status')->updateOrInsert(
                ['name' => $status],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}