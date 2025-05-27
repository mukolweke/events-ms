<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('organizations')->insert([
            [
                'name' => 'Test Organization',
                'slug' => 'test-org',
                'domain' => 'test.local',
                'settings' => json_encode(['theme' => 'light']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Demo Organization',
                'slug' => 'demo-org',
                'domain' => 'demo.local',
                'settings' => json_encode(['theme' => 'dark']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
