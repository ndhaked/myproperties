<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeadCategory;

class LeadCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Options from the screenshot
        $options = [
            'Primary',
            'Secondary',
            'OffPlan Secondary'
        ];

        foreach ($options as $option) {
            LeadCategory::updateOrCreate(
                ['name' => $option],
                ['name' => $option]
            );
        }
    }
}