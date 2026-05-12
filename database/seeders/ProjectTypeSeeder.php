<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectType;
use Illuminate\Support\Str;

class ProjectTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Options extracted from your screenshot
        $options = [
            'Primary',
            'Secondary',
            'Commercial',
            'Facility Management',
            'Residential Leasing',
            'Commercial Leasing'
        ];

        foreach ($options as $option) {
            ProjectType::updateOrCreate(
                ['name' => $option],
                [
                    'name' => $option,
                    'slug' => Str::slug($option), // Auto-generates slug
                    'status' => 1
                ]
            );
        }
    }
}