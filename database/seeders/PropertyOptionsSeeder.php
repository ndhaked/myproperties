<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Category;
use App\Models\City;
use App\Models\Highlight;
use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertyOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Plot / Land', 'Apartment', 'Villa / House'] as $i => $name) {
            PropertyType::firstOrCreate(['name' => $name], ['sort_order' => $i]);
        }

        $categories = [
            ['title' => 'Pre-Launch Properties', 'subtitle' => 'Upcoming projects at best prices', 'icon' => 'rocket', 'color' => '#d8cdf7ff', 'icon_color' => '#8B5CF6'],
            ['title' => 'Ready to Move', 'subtitle' => 'Move in without waiting', 'icon' => 'home', 'color' => '#cdeef7ff', 'icon_color' => '#0EA5E9'],
            ['title' => 'Under Construction', 'subtitle' => 'Under development projects', 'icon' => 'building', 'color' => '#fde8cd', 'icon_color' => '#F59E0B'],
        ];
        foreach ($categories as $i => $category) {
            Category::firstOrCreate(['title' => $category['title']], [...$category, 'sort_order' => $i]);
        }

        foreach (['Bangalore', 'Pune', 'Hyderabad', 'Chennai'] as $i => $name) {
            City::firstOrCreate(['name' => $name], ['sort_order' => $i]);
        }

        $amenities = [
            ['name' => 'Water Supply', 'icon' => 'water_drop'],
            ['name' => 'Electricity', 'icon' => 'bolt'],
            ['name' => 'Gated Community', 'icon' => 'lock'],
            ['name' => 'Security', 'icon' => 'shield'],
            ['name' => 'Black Top Road', 'icon' => 'road'],
            ['name' => 'Street Lights', 'icon' => 'lightbulb'],
        ];
        foreach ($amenities as $amenity) {
            Amenity::firstOrCreate(['name' => $amenity['name']], $amenity);
        }

        foreach (['Corner Plot', 'Vastu Compliant', 'East Facing', 'Clear Title', 'Close to Main Road', 'Bank Loan Available'] as $name) {
            Highlight::firstOrCreate(['name' => $name]);
        }
    }
}
