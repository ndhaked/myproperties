<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PurposeType; // Import the new model

class PurposeTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Options from your screenshot
        $options = [
            'Apartment',
            'Commercial',
            'Duplex',
            'Floor',
            'Land',
            'Office',
            'Penthouse',
            'Townhouse',
            'Villa'
        ];

        foreach ($options as $option) {
            // Check if name exists; if yes, update it (touch timestamp), if no, create it.
            PurposeType::updateOrCreate(
                ['name' => $option], 
                ['name' => $option]
            );
        }
    }
}