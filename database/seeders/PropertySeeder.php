<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Category;
use App\Models\City;
use App\Models\Highlight;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = City::all();
        $categories = Category::all();
        $propertyTypes = PropertyType::all();
        $amenities = Amenity::all();
        $highlights = Highlight::all();

        if ($cities->isEmpty() || $categories->isEmpty() || $propertyTypes->isEmpty()) {
            $this->command?->warn('Skipping PropertySeeder: run PropertyOptionsSeeder first.');
            return;
        }

        $properties = [
            ['title' => 'Greenfield Paradise', 'price' => 4850000, 'min' => 1200, 'max' => 2400, 'size' => '30 X 40, 40 X 60', 'facing' => 'East', 'corner' => true, 'age' => 'New Property', 'status' => 'Active'],
            ['title' => 'Sunrise Meadows', 'price' => 6200000, 'min' => 1500, 'max' => 3000, 'size' => '30 X 50, 40 X 60', 'facing' => 'North', 'corner' => false, 'age' => 'New Property', 'status' => 'Active'],
            ['title' => 'Whitefield Heights Apartment', 'price' => 8900000, 'min' => 1100, 'max' => 1100, 'size' => '3 BHK', 'facing' => 'West', 'corner' => false, 'age' => '1-5 Years', 'status' => 'Active'],
            ['title' => 'Lakeview Residency Villa', 'price' => 15500000, 'min' => 2400, 'max' => 2400, 'size' => '4 BHK', 'facing' => 'South', 'corner' => true, 'age' => 'New Property', 'status' => 'Active'],
            ['title' => 'Palm Springs Plots', 'price' => 3200000, 'min' => 1000, 'max' => 1800, 'size' => '30 X 40', 'facing' => 'East', 'corner' => false, 'age' => 'New Property', 'status' => 'Inactive'],
            ['title' => 'Orchid County Villas', 'price' => 21000000, 'min' => 3000, 'max' => 3000, 'size' => '5 BHK', 'facing' => 'North', 'corner' => true, 'age' => '5-10 Years', 'status' => 'Active'],
            ['title' => 'Silver Oaks Apartment', 'price' => 5600000, 'min' => 950, 'max' => 950, 'size' => '2 BHK', 'facing' => 'West', 'corner' => false, 'age' => '1-5 Years', 'status' => 'Sold'],
            ['title' => 'Emerald Hills Plots', 'price' => 4100000, 'min' => 1200, 'max' => 2000, 'size' => '30 X 40, 40 X 50', 'facing' => 'South', 'corner' => false, 'age' => 'New Property', 'status' => 'Active'],
            ['title' => 'Maple Grove House', 'price' => 9800000, 'min' => 1800, 'max' => 1800, 'size' => '3 BHK', 'facing' => 'East', 'corner' => true, 'age' => '10+ Years', 'status' => 'Active'],
            ['title' => 'Crystal Bay Villas', 'price' => 18500000, 'min' => 2800, 'max' => 2800, 'size' => '4 BHK', 'facing' => 'North', 'corner' => false, 'age' => 'New Property', 'status' => 'Active'],
            ['title' => 'Golden Meadows Plots', 'price' => 2800000, 'min' => 900, 'max' => 1500, 'size' => '30 X 30, 30 X 40', 'facing' => 'West', 'corner' => false, 'age' => 'New Property', 'status' => 'Inactive'],
            ['title' => 'Royal Enclave Apartment', 'price' => 7300000, 'min' => 1250, 'max' => 1250, 'size' => '3 BHK', 'facing' => 'South', 'corner' => false, 'age' => '1-5 Years', 'status' => 'Active'],
        ];

        $nearbyPool = [
            ['name' => 'Oakridge International School', 'category' => 'school'],
            ['name' => 'Delhi Public School', 'category' => 'school'],
            ['name' => 'Apollo Hospital', 'category' => 'hospital'],
            ['name' => 'Columbia Asia Hospital', 'category' => 'hospital'],
            ['name' => 'Phoenix MarketCity Mall', 'category' => 'mall'],
            ['name' => 'Forum Mall', 'category' => 'mall'],
            ['name' => 'Metro Station', 'category' => 'transport'],
            ['name' => 'National Highway Junction', 'category' => 'transport'],
        ];

        $colors = ['4F46E5', '0EA5E9', 'F59E0B', '10B981', 'EC4899', '8B5CF6'];

        foreach ($properties as $index => $data) {
            $city = $cities[$index % $cities->count()];
            $category = $categories[$index % $categories->count()];
            $propertyType = $propertyTypes[$index % $propertyTypes->count()];

            $property = Property::create([
                'title' => $data['title'],
                'category_id' => $category->id,
                'property_type_id' => $propertyType->id,
                'location_line' => "Sample Road, {$city->name}",
                'city_id' => $city->id,
                'state' => 'Karnataka',
                'pincode' => '560' . str_pad((string) (35 + $index), 3, '0', STR_PAD_LEFT),
                'lat' => 12.9 + ($index * 0.01),
                'lng' => 77.6 + ($index * 0.01),
                'price_amount' => $data['price'],
                'price_per_sqft' => round($data['price'] / max($data['max'], 1), 2),
                'plot_area_min' => $data['min'],
                'plot_area_max' => $data['max'],
                'plot_size' => $data['size'],
                'facing' => $data['facing'],
                'is_corner_plot' => $data['corner'],
                'property_age' => $data['age'],
                'possession_date' => now()->addMonths(rand(1, 18))->format('Y-m-d'),
                'road_width' => rand(2, 6) * 10 . ' Ft',
                'description' => "Premium {$propertyType->name} in a prime location of {$city->name}. Well-connected, secure, and ready for immediate registration.",
                'badge_label' => $category->title,
                'badge_color' => $category->icon_color,
                'offer_text' => $index % 3 === 0 ? $category->title . ' Offer' : null,
                'offer_highlight' => $index % 3 === 0 ? 'Save up to ' . rand(5, 20) . '%' : null,
                'offer_bg_color' => $category->color,
                'offer_text_color' => $category->icon_color,
                'status' => $data['status'],
                'views' => rand(5, 300),
                'is_featured' => $index % 4 === 0,
            ]);

            if ($highlights->isNotEmpty()) {
                $property->highlights()->attach($highlights->random(min(3, $highlights->count()))->pluck('id'));
            }

            if ($amenities->isNotEmpty()) {
                $property->amenities()->attach($amenities->random(min(4, $amenities->count()))->pluck('id'));
            }

            foreach (array_rand($nearbyPool, min(2, count($nearbyPool))) as $placeIndex) {
                $place = $nearbyPool[$placeIndex];

                $property->nearbyPlaces()->create([
                    'name' => $place['name'],
                    'distance_km' => round(rand(5, 50) / 10, 1),
                    'category' => $place['category'],
                ]);
            }

            $color = $colors[$index % count($colors)];
            PropertyImage::create([
                'property_id' => $property->id,
                'path' => $this->makePlaceholderImage($property->id, $data['title'], $color),
                'sort_order' => 0,
            ]);
        }
    }

    protected function makePlaceholderImage(int $propertyId, string $title, string $hexColor): string
    {
        $width = 640;
        $height = 400;
        $image = imagecreatetruecolor($width, $height);

        [$r, $g, $b] = sscanf($hexColor, '%02x%02x%02x');
        $bg = imagecolorallocate($image, $r, $g, $b);
        imagefill($image, 0, 0, $bg);

        $white = imagecolorallocate($image, 255, 255, 255);
        $text = wordwrap($title, 20, "\n", true);
        imagestring($image, 5, 20, $height / 2 - 10, $text, $white);

        ob_start();
        imagepng($image);
        $contents = ob_get_clean();
        imagedestroy($image);

        $path = "properties/{$propertyId}/images/placeholder-" . uniqid() . '.png';
        Storage::disk('public')->put($path, $contents);

        return $path;
    }
}
