<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Organization;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Get the test organization
        $organization = Organization::where('slug', 'test-org')->first();

        if (!$organization) {
            throw new \Exception('Test organization not found. Please run OrganizationSeeder first.');
        }

        DB::table('users')->insert([
            [
                'organization_id' => $organization->id,
                'name' => 'Admin User',
                'email' => 'admin@test.local',
                'password' => Hash::make('Password123!'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organization->id,
                'name' => 'Regular User',
                'email' => 'user@test.local',
                'password' => Hash::make('Password123!'),
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
