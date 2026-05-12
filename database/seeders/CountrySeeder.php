<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use DB;
use Spatie\Permission\Models\Role;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Disable
        Country::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Enable

        $path = public_path('countries.csv');
        $data = array_map('str_getcsv', file($path));

        // Skip the header row
        $header = array_shift($data);

        foreach ($data as $row) {

            // Skip empty rows
            if (count($row) < 6) {
                continue;
            }

            Country::updateOrCreate(
                [
                    // Unique key condition
                    'country_code' => $row[5],
                ],
                [
                    'country_of_residence' => $row[0],
                    'country_of_birth' => $row[1],
                    'nationality' => $row[2],
                    'regional_affiliation_country' => $row[3],
                    'country' => $row[4],
                    'country_code' => $row[5],
                ]
            );
        }
    }

}
