<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Amenity;
use App\Models\Highlight;
use App\Models\Property;
use App\Models\PropertyType;

class ConfigController extends Controller
{
    public function propertyFormOptions()
    {
        return ApiResponse::success([
            'propertyTypes' => PropertyType::orderBy('sort_order')->pluck('name'),
            'highlightOptions' => Highlight::orderBy('name')->pluck('name'),
            'amenityOptions' => Amenity::orderBy('name')->pluck('name'),
            'facingOptions' => Property::FACING_OPTIONS,
            'propertyAgeOptions' => Property::PROPERTY_AGE_OPTIONS,
        ]);
    }

    public function countries()
    {
        return ApiResponse::success([
            ['code' => '+91', 'iso' => 'IN', 'name' => 'India', 'flag' => '🇮🇳'],
            ['code' => '+1', 'iso' => 'US', 'name' => 'United States', 'flag' => '🇺🇸'],
            ['code' => '+44', 'iso' => 'GB', 'name' => 'United Kingdom', 'flag' => '🇬🇧'],
            ['code' => '+971', 'iso' => 'AE', 'name' => 'United Arab Emirates', 'flag' => '🇦🇪'],
        ]);
    }

    public function app()
    {
        return ApiResponse::success([
            'supportPhone' => config('app.support_phone', '+911800123456'),
            'supportEmail' => config('mail.from.address', 'support@estara.com'),
            'termsUrl' => config('app.url') . '/terms',
            'privacyUrl' => config('app.url') . '/privacy',
        ]);
    }
}
