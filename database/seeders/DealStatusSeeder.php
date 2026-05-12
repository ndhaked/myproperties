<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DealStatus;
use Illuminate\Support\Str;

class DealStatusSeeder extends Seeder
{
    public function run(): void
    {
        // Options extracted from your screenshot
        $options = [
            'Approved',
            'Pending',
            'Cancelled',
            'Commission Released',
            'Under Signed',
            'Partial Commission',
            'Under Collection'
        ];

        foreach ($options as $option) {
            DealStatus::updateOrCreate(
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