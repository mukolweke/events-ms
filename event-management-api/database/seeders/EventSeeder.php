<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('events')->insert([
            [
                'organization_id' => 1,
                'title' => 'Annual Conference 2024',
                'description' => 'Join us for our annual conference featuring industry experts and networking opportunities.',
                'venue' => 'Convention Center',
                'event_date' => now()->addMonths(2),
                'price' => 299.99,
                'max_attendees' => 500,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 1,
                'title' => 'Workshop: Web Development',
                'description' => 'Learn modern web development techniques in this hands-on workshop.',
                'venue' => 'Tech Hub',
                'event_date' => now()->addMonths(1),
                'price' => 99.99,
                'max_attendees' => 50,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
