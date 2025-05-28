<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        DB::table('organizations')->insert([
            [
                'name' => $faker->company,
                'slug' => Str::slug($faker->company),
                'domain' => $faker->domainName,
                'email' => $faker->companyEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'description' => $faker->paragraph,
                'settings' => json_encode(['theme' => 'light']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => $faker->company,
                'slug' => Str::slug($faker->company),
                'domain' => $faker->domainName,
                'email' => $faker->companyEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'description' => $faker->paragraph,
                'settings' => json_encode(['theme' => 'dark']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
