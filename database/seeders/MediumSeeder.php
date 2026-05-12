<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medium;

class MediumSeeder extends Seeder
{
    public function run(): void
    {
        // Options extracted from your screenshot
        $options = [
            'organic',
            'direct',
            'social',
            'paid-social',
            'cpc'
        ];

        foreach ($options as $option) {
            Medium::updateOrCreate(
                ['name' => $option],
                ['name' => $option]
            );
        }
    }
}