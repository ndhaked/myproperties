<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Timezone;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TimezoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define comprehensive timezone data
        $timezones = [
            // UTC
            ['timezone' => 'UTC', 'name' => 'UTC (Coordinated Universal Time)', 'abbreviation' => 'UTC', 'offset' => 'UTC+00:00', 'offset_seconds' => 0, 'is_active' => true, 'sort_order' => 1],

            // North America
            ['timezone' => 'America/New_York', 'name' => 'Eastern Standard Time (EST)', 'abbreviation' => 'EST', 'offset' => 'UTC-05:00', 'offset_seconds' => -18000, 'is_active' => true, 'sort_order' => 10],
            ['timezone' => 'America/Chicago', 'name' => 'Central Standard Time (CST)', 'abbreviation' => 'CST', 'offset' => 'UTC-06:00', 'offset_seconds' => -21600, 'is_active' => true, 'sort_order' => 11],
            ['timezone' => 'America/Denver', 'name' => 'Mountain Standard Time (MST)', 'abbreviation' => 'MST', 'offset' => 'UTC-07:00', 'offset_seconds' => -25200, 'is_active' => true, 'sort_order' => 12],
            ['timezone' => 'America/Los_Angeles', 'name' => 'Pacific Standard Time (PST)', 'abbreviation' => 'PST', 'offset' => 'UTC-08:00', 'offset_seconds' => -28800, 'is_active' => true, 'sort_order' => 13],
            ['timezone' => 'America/Phoenix', 'name' => 'Mountain Standard Time - Arizona', 'abbreviation' => 'MST', 'offset' => 'UTC-07:00', 'offset_seconds' => -25200, 'is_active' => true, 'sort_order' => 14],
            ['timezone' => 'America/Anchorage', 'name' => 'Alaska Standard Time (AKST)', 'abbreviation' => 'AKST', 'offset' => 'UTC-09:00', 'offset_seconds' => -32400, 'is_active' => true, 'sort_order' => 15],
            ['timezone' => 'Pacific/Honolulu', 'name' => 'Hawaii-Aleutian Standard Time (HST)', 'abbreviation' => 'HST', 'offset' => 'UTC-10:00', 'offset_seconds' => -36000, 'is_active' => true, 'sort_order' => 16],

            // Europe
            ['timezone' => 'Europe/London', 'name' => 'Greenwich Mean Time (GMT)', 'abbreviation' => 'GMT', 'offset' => 'UTC+00:00', 'offset_seconds' => 0, 'is_active' => true, 'sort_order' => 20],
            ['timezone' => 'Europe/Paris', 'name' => 'Central European Time (CET)', 'abbreviation' => 'CET', 'offset' => 'UTC+01:00', 'offset_seconds' => 3600, 'is_active' => true, 'sort_order' => 21],
            ['timezone' => 'Europe/Berlin', 'name' => 'Central European Time - Germany', 'abbreviation' => 'CET', 'offset' => 'UTC+01:00', 'offset_seconds' => 3600, 'is_active' => true, 'sort_order' => 22],
            ['timezone' => 'Europe/Rome', 'name' => 'Central European Time - Italy', 'abbreviation' => 'CET', 'offset' => 'UTC+01:00', 'offset_seconds' => 3600, 'is_active' => true, 'sort_order' => 23],
            ['timezone' => 'Europe/Madrid', 'name' => 'Central European Time - Spain', 'abbreviation' => 'CET', 'offset' => 'UTC+01:00', 'offset_seconds' => 3600, 'is_active' => true, 'sort_order' => 24],
            ['timezone' => 'Europe/Amsterdam', 'name' => 'Central European Time - Netherlands', 'abbreviation' => 'CET', 'offset' => 'UTC+01:00', 'offset_seconds' => 3600, 'is_active' => true, 'sort_order' => 25],
            ['timezone' => 'Europe/Athens', 'name' => 'Eastern European Time (EET)', 'abbreviation' => 'EET', 'offset' => 'UTC+02:00', 'offset_seconds' => 7200, 'is_active' => true, 'sort_order' => 26],
            ['timezone' => 'Europe/Moscow', 'name' => 'Moscow Standard Time (MSK)', 'abbreviation' => 'MSK', 'offset' => 'UTC+03:00', 'offset_seconds' => 10800, 'is_active' => true, 'sort_order' => 27],

            // Middle East
            ['timezone' => 'Asia/Dubai', 'name' => 'Gulf Standard Time (GST)', 'abbreviation' => 'GST', 'offset' => 'UTC+04:00', 'offset_seconds' => 14400, 'is_active' => true, 'sort_order' => 30],
            ['timezone' => 'Asia/Riyadh', 'name' => 'Arabia Standard Time (AST)', 'abbreviation' => 'AST', 'offset' => 'UTC+03:00', 'offset_seconds' => 10800, 'is_active' => true, 'sort_order' => 31],
            ['timezone' => 'Asia/Tehran', 'name' => 'Iran Standard Time (IRST)', 'abbreviation' => 'IRST', 'offset' => 'UTC+03:30', 'offset_seconds' => 12600, 'is_active' => true, 'sort_order' => 32],
            ['timezone' => 'Asia/Jerusalem', 'name' => 'Israel Standard Time (IST)', 'abbreviation' => 'IST', 'offset' => 'UTC+02:00', 'offset_seconds' => 7200, 'is_active' => true, 'sort_order' => 33],

            // Asia
            ['timezone' => 'Asia/Kolkata', 'name' => 'Indian Standard Time (IST)', 'abbreviation' => 'IST', 'offset' => 'UTC+05:30', 'offset_seconds' => 19800, 'is_active' => true, 'sort_order' => 40],
            ['timezone' => 'Asia/Dhaka', 'name' => 'Bangladesh Standard Time (BST)', 'abbreviation' => 'BST', 'offset' => 'UTC+06:00', 'offset_seconds' => 21600, 'is_active' => true, 'sort_order' => 41],
            ['timezone' => 'Asia/Bangkok', 'name' => 'Indochina Time (ICT)', 'abbreviation' => 'ICT', 'offset' => 'UTC+07:00', 'offset_seconds' => 25200, 'is_active' => true, 'sort_order' => 42],
            ['timezone' => 'Asia/Singapore', 'name' => 'Singapore Standard Time (SGT)', 'abbreviation' => 'SGT', 'offset' => 'UTC+08:00', 'offset_seconds' => 28800, 'is_active' => true, 'sort_order' => 43],
            ['timezone' => 'Asia/Hong_Kong', 'name' => 'Hong Kong Time (HKT)', 'abbreviation' => 'HKT', 'offset' => 'UTC+08:00', 'offset_seconds' => 28800, 'is_active' => true, 'sort_order' => 44],
            ['timezone' => 'Asia/Shanghai', 'name' => 'China Standard Time (CST)', 'abbreviation' => 'CST', 'offset' => 'UTC+08:00', 'offset_seconds' => 28800, 'is_active' => true, 'sort_order' => 45],
            ['timezone' => 'Asia/Tokyo', 'name' => 'Japan Standard Time (JST)', 'abbreviation' => 'JST', 'offset' => 'UTC+09:00', 'offset_seconds' => 32400, 'is_active' => true, 'sort_order' => 46],
            ['timezone' => 'Asia/Seoul', 'name' => 'Korea Standard Time (KST)', 'abbreviation' => 'KST', 'offset' => 'UTC+09:00', 'offset_seconds' => 32400, 'is_active' => true, 'sort_order' => 47],

            // Australia & Pacific
            ['timezone' => 'Australia/Sydney', 'name' => 'Australian Eastern Time (AET)', 'abbreviation' => 'AET', 'offset' => 'UTC+10:00', 'offset_seconds' => 36000, 'is_active' => true, 'sort_order' => 50],
            ['timezone' => 'Australia/Melbourne', 'name' => 'Australian Eastern Time - Melbourne', 'abbreviation' => 'AET', 'offset' => 'UTC+10:00', 'offset_seconds' => 36000, 'is_active' => true, 'sort_order' => 51],
            ['timezone' => 'Australia/Brisbane', 'name' => 'Australian Eastern Standard Time (AEST)', 'abbreviation' => 'AEST', 'offset' => 'UTC+10:00', 'offset_seconds' => 36000, 'is_active' => true, 'sort_order' => 52],
            ['timezone' => 'Australia/Adelaide', 'name' => 'Australian Central Time (ACT)', 'abbreviation' => 'ACT', 'offset' => 'UTC+09:30', 'offset_seconds' => 34200, 'is_active' => true, 'sort_order' => 53],
            ['timezone' => 'Australia/Perth', 'name' => 'Australian Western Time (AWT)', 'abbreviation' => 'AWT', 'offset' => 'UTC+08:00', 'offset_seconds' => 28800, 'is_active' => true, 'sort_order' => 54],
            ['timezone' => 'Pacific/Auckland', 'name' => 'New Zealand Standard Time (NZST)', 'abbreviation' => 'NZST', 'offset' => 'UTC+12:00', 'offset_seconds' => 43200, 'is_active' => true, 'sort_order' => 55],

            // South America
            ['timezone' => 'America/Sao_Paulo', 'name' => 'Brasilia Time (BRT)', 'abbreviation' => 'BRT', 'offset' => 'UTC-03:00', 'offset_seconds' => -10800, 'is_active' => true, 'sort_order' => 60],
            ['timezone' => 'America/Argentina/Buenos_Aires', 'name' => 'Argentina Time (ART)', 'abbreviation' => 'ART', 'offset' => 'UTC-03:00', 'offset_seconds' => -10800, 'is_active' => true, 'sort_order' => 61],
            ['timezone' => 'America/Lima', 'name' => 'Peru Time (PET)', 'abbreviation' => 'PET', 'offset' => 'UTC-05:00', 'offset_seconds' => -18000, 'is_active' => true, 'sort_order' => 62],
            ['timezone' => 'America/Mexico_City', 'name' => 'Central Standard Time - Mexico', 'abbreviation' => 'CST', 'offset' => 'UTC-06:00', 'offset_seconds' => -21600, 'is_active' => true, 'sort_order' => 63],
        ];

        // Insert or update timezones (prevents duplicates)
        foreach ($timezones as $timezoneData) {
            Timezone::updateOrCreate(
                [
                    // Unique key condition
                    'timezone' => $timezoneData['timezone'],
                ],
                [
                    // Data to update or create
                    'name' => $timezoneData['name'],
                    'abbreviation' => $timezoneData['abbreviation'],
                    'offset' => $timezoneData['offset'],
                    'offset_seconds' => $timezoneData['offset_seconds'],
                    'is_active' => $timezoneData['is_active'],
                    'sort_order' => $timezoneData['sort_order'],
                ]
            );
        }

        // Set default timezone for existing users who don't have one set
        User::whereNull('timezone')
            ->orWhere('timezone', '')
            ->update(['timezone' => 'UTC']);

        $this->command->info('Timezones seeded successfully!');
    }
}
