<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Source;

class SourceSeeder extends Seeder
{
    public function run(): void
    {
        // Options visible in your screenshot
        $options = [
            'Application',
            'bayut',
            'Cityscape',
            'Dubizzle',
            'email',
            'facebook',
            'google',
            'hytham',
            'instagram',
            'linkedin',
            'Mall Stand',
            'migel_google',
            'native-colombia',
            'native-speakol',
            'optimizeapp',
            'Personal',
            'property-finder',
            'Referral',
            'Russian Vloggers'
        ];

        foreach ($options as $option) {
            Source::updateOrCreate(
                ['name' => $option],
                ['name' => $option]
            );
        }
    }
}