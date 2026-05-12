<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purpose; 

class PurposeSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            'rent',
            'buy',
            'sell',
            'management',
            'handover'
        ];

        foreach ($options as $option) {
            // 2. Use updateOrCreate
            // Argument 1: The condition to search for (e.g., does name = 'rent'?)
            // Argument 2: The values to update/create.
            Purpose::updateOrCreate(
                ['name' => $option], 
                ['name' => $option] 
            );
        }
    }
}