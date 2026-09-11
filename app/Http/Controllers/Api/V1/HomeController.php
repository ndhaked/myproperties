<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Http\Responses\ApiResponse;
use App\Models\Category;
use App\Models\City;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    protected const TRUST_FEATURES = [
        ['title' => 'No Brokerage', 'icon' => 'no_brokerage'],
        ['title' => 'Verified Listings', 'icon' => 'verified'],
        ['title' => 'Direct Owner Contact', 'icon' => 'contact_phone'],
        ['title' => 'Legal Assistance', 'icon' => 'gavel'],
    ];

    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($category) => [
                'id' => 'cat_' . $category->id,
                'title' => $category->title,
                'subtitle' => $category->subtitle,
                'icon' => $category->icon,
                'color' => $category->color,
                'iconColor' => $category->icon_color,
            ]);

        $popularCities = City::where('is_popular', true)
            ->withCount('properties')
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($city) => [
                'name' => $city->name,
                'image' => $city->image ? Storage::url($city->image) : null,
                'propertyCount' => $city->properties_count,
            ]);

        $featuredProperties = Property::where('status', 'Active')
            ->where('is_featured', true)
            ->with(['category', 'propertyType', 'city', 'images', 'amenities', 'highlights', 'nearbyPlaces', 'documents', 'owner'])
            ->latest()
            ->limit(10)
            ->get();

        return ApiResponse::success([
            'categories' => $categories,
            'trustFeatures' => self::TRUST_FEATURES,
            'popularCities' => $popularCities,
            'featuredProperties' => PropertyResource::collection($featuredProperties),
            'exclusiveOffer' => [
                'title' => 'Exclusive Offers',
                'subtitle' => 'Limited time offers on top projects',
                'ctaLabel' => 'Explore Offers',
                'route' => 'OffersListing',
            ],
        ]);
    }

    public function search(Request $request)
    {
        $data = $request->validate(['q' => ['nullable', 'string']]);

        $properties = Property::where('status', 'Active')
            ->with(['category', 'propertyType', 'city', 'images', 'amenities', 'highlights', 'nearbyPlaces', 'documents', 'owner'])
            ->when($data['q'] ?? null, function ($query, $q) {
                $query->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                        ->orWhere('location_line', 'like', "%{$q}%")
                        ->orWhereHas('city', fn ($cityQuery) => $cityQuery->where('name', 'like', "%{$q}%"));
                });
            })
            ->paginate((int) $request->input('limit', 10));

        return ApiResponse::success(ApiResponse::paginated($properties, fn ($property) => new PropertyResource($property)));
    }
}
