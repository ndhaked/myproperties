<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Http\Responses\ApiResponse;
use App\Models\AppNotification;
use App\Models\Category;
use App\Models\City;
use App\Models\Lead;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    protected const RELATIONS = ['category', 'propertyType', 'city', 'images', 'amenities', 'highlights', 'nearbyPlaces', 'documents', 'owner'];

    public function index(Request $request)
    {
        $data = $request->validate([
            'category' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'q' => ['nullable', 'string'],
            'propertyType' => ['nullable', 'string'],
            'minPrice' => ['nullable', 'numeric'],
            'maxPrice' => ['nullable', 'numeric'],
            'minSize' => ['nullable', 'numeric'],
            'maxSize' => ['nullable', 'numeric'],
            'facing' => ['nullable', 'string'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $category = isset($data['category']) ? Category::where('title', $data['category'])->first() : null;

        $query = Property::query()->with(self::RELATIONS)
            ->when($category, fn ($q) => $q->where('category_id', $category->id))
            ->when($data['city'] ?? null, fn ($q, $city) => $q->whereHas('city', fn ($cq) => $cq->where('name', $city)))
            ->when($data['q'] ?? null, fn ($q, $term) => $q->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")->orWhere('location_line', 'like', "%{$term}%");
            }))
            ->when($data['propertyType'] ?? null, fn ($q, $type) => $q->whereHas('propertyType', fn ($tq) => $tq->where('name', $type)))
            ->when($data['minPrice'] ?? null, fn ($q, $v) => $q->where('price_amount', '>=', $v))
            ->when($data['maxPrice'] ?? null, fn ($q, $v) => $q->where('price_amount', '<=', $v))
            ->when($data['minSize'] ?? null, fn ($q, $v) => $q->where('plot_area_max', '>=', $v))
            ->when($data['maxSize'] ?? null, fn ($q, $v) => $q->where('plot_area_min', '<=', $v))
            ->when($data['facing'] ?? null, fn ($q, $v) => $q->where('facing', $v))
            ->where('status', 'Active')
            ->latest();

        $properties = $query->paginate((int) ($data['limit'] ?? 10));

        $result = ApiResponse::paginated($properties, fn ($property) => new PropertyResource($property));

        $result['banner'] = $category ? [
            'title' => $category->title,
            'subtitle' => $category->subtitle,
            'image' => null,
        ] : null;

        return ApiResponse::success($result);
    }

    public function show(Request $request, Property $property)
    {
        $property->load(self::RELATIONS);
        $property->increment('views');

        return ApiResponse::success([
            ...(new PropertyResource($property))->toArray($request),
            'mapImageUrl' => null,
        ]);
    }

    public function save(Request $request, Property $property)
    {
        $request->user()->savedProperties()->syncWithoutDetaching([$property->id]);

        return ApiResponse::success(['isSaved' => true]);
    }

    public function unsave(Request $request, Property $property)
    {
        $request->user()->savedProperties()->detach($property->id);

        return ApiResponse::success(['isSaved' => false]);
    }

    public function filterOptions()
    {
        return ApiResponse::success([
            'propertyTypes' => PropertyType::orderBy('sort_order')->pluck('name'),
            'cities' => City::orderBy('name')->pluck('name'),
            'facingOptions' => Property::FACING_OPTIONS,
            'priceRange' => [
                'min' => (int) (Property::min('price_amount') ?? 0),
                'max' => (int) (Property::max('price_amount') ?? 0),
            ],
            'sizeRangeSqft' => [
                'min' => (int) (Property::min('plot_area_min') ?? 0),
                'max' => (int) (Property::max('plot_area_max') ?? 0),
            ],
        ]);
    }

    public function contact(Request $request, Property $property)
    {
        $data = $request->validate([
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $lead = Lead::create([
            'property_id' => $property->id,
            'buyer_id' => $request->user()->id,
            'message' => $data['message'] ?? null,
        ]);

        if ($property->owner_id) {
            AppNotification::create([
                'user_id' => $property->owner_id,
                'title' => 'New enquiry received',
                'body' => "{$request->user()->name} is interested in \"{$property->title}\".",
            ]);
        }

        return ApiResponse::success([
            'leadId' => 'lead_' . $lead->id,
            'ownerPhone' => $property->owner ? $property->owner->country_code . $property->owner->phone : null,
        ]);
    }
}
