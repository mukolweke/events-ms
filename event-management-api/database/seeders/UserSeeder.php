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
        $organization = Organization::first();

        if (!$organization) {
            throw new \Exception('Test organization not found. Please run OrganizationSeeder first.');
        }

        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@test.local',
                'password' => Hash::make('Password123!'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@test.local',
                'password' => Hash::make('Password123!'),
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Associate users with the organization in the pivot table
        $adminUser = DB::table('users')->where('email', 'admin@test.local')->first();
        $regularUser = DB::table('users')->where('email', 'user@test.local')->first();

        DB::table('organization_user')->insert([
            [
                'user_id' => $adminUser->id,
                'organization_id' => $organization->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $regularUser->id,
                'organization_id' => $organization->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
