<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            TimezoneSeeder::class,
            RolePermissionSeeder::class,
            DealStatusSeeder::class,
            LeadCategorySeeder::class,
            MediumSeeder::class,
            ProjectTypeSeeder::class,
            PurposeSeeder::class,
            PurposeTypeSeeder::class,
            SourceSeeder::class,
        ]);
    }
}
