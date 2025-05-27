<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendeeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('attendees')->insert([
            [
                'event_id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+1234567890',
                'registered_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 1,
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '+1987654321',
                'registered_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 2,
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'phone' => '+1122334455',
                'registered_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
