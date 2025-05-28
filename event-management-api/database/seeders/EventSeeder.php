<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Organization;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::first();

        if (!$organization) {
            throw new \Exception('No organization found. Please run OrganizationSeeder first.');
        }

        $startDate = now()->addMonths(2);
        $endDate = (clone $startDate)->addHours(2);

        DB::table('events')->insert([
            [
                'organization_id' => $organization->id,
                'title' => 'Annual Conference 2024',
                'description' => 'Join us for our annual conference featuring industry experts and networking opportunities.',
                'venue' => 'Convention Center',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'price' => 299.99,
                'max_attendees' => 500,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organization->id,
                'title' => 'Workshop: Web Development',
                'description' => 'Learn modern web development techniques in this hands-on workshop.',
                'venue' => 'Tech Hub',
                'start_date' => now()->addMonths(1),
                'end_date' => now()->addMonths(1)->addHours(3),
                'price' => 99.99,
                'max_attendees' => 50,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
