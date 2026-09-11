<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Http\Responses\ApiResponse;
use App\Models\Amenity;
use App\Models\BusinessProfile;
use App\Models\Category;
use App\Models\City;
use App\Models\Highlight;
use App\Models\Lead;
use App\Models\PayoutSetting;
use App\Models\Property;
use App\Models\PropertyDocument;
use App\Models\PropertyImage;
use App\Models\PropertyType;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SellerController extends Controller
{
    protected const RELATIONS = ['category', 'propertyType', 'city', 'images', 'amenities', 'highlights', 'nearbyPlaces', 'documents', 'owner'];

    public function dashboard(Request $request)
    {
        $user = $request->user();
        $listingIds = $user->listings()->pluck('id');

        return ApiResponse::success([
            'seller' => [
                'fullName' => $user->name,
                'isVerified' => (bool) $user->is_verified,
            ],
            'stats' => [
                'myListings' => ['value' => $listingIds->count(), 'subValue' => 'Active'],
                'bookedVisits' => ['value' => Visit::whereIn('property_id', $listingIds)->where('status', 'Upcoming')->count(), 'subValue' => 'Upcoming'],
                'profileViews' => ['value' => (int) Property::whereIn('id', $listingIds)->sum('views'), 'subValue' => 'This Month'],
                'totalLeads' => ['value' => Lead::whereIn('property_id', $listingIds)->whereMonth('created_at', now()->month)->count(), 'subValue' => 'This Month'],
            ],
            'boostBanner' => [
                'title' => 'Boost Your Listings',
                'subtitle' => 'Get more visibility and leads by promoting your properties.',
                'ctaLabel' => 'Promote Now',
            ],
        ]);
    }

    public function listings(Request $request)
    {
        $properties = $request->user()->listings()
            ->with(self::RELATIONS)
            ->latest()
            ->paginate((int) $request->input('limit', 10));

        return ApiResponse::success(ApiResponse::paginated($properties, fn ($property) => new PropertyResource($property)));
    }

    public function showListing(Request $request, Property $property)
    {
        $this->authorizeOwner($request, $property);

        $property->load(self::RELATIONS);

        return ApiResponse::success(new PropertyResource($property));
    }

    public function storeListing(Request $request)
    {
        $data = $this->validateListing($request);

        $property = new Property(['owner_id' => $request->user()->id]);
        $this->fillListing($property, $data);
        $property->save();

        $this->syncListingRelations($request, $property, $data);

        return ApiResponse::success(new PropertyResource($property->load(self::RELATIONS)), 'Property created.', 201);
    }

    public function updateListing(Request $request, Property $property)
    {
        $this->authorizeOwner($request, $property);

        $data = $this->validateListing($request);

        $this->fillListing($property, $data);
        $property->save();

        $this->syncListingRelations($request, $property, $data);

        return ApiResponse::success(new PropertyResource($property->load(self::RELATIONS)), 'Property updated.');
    }

    public function destroyListing(Request $request, Property $property)
    {
        $this->authorizeOwner($request, $property);

        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        foreach ($property->documents as $document) {
            Storage::disk('public')->delete($document->path);
        }

        $property->delete();

        return ApiResponse::success(null, 'Property deleted.');
    }

    public function performance(Request $request, Property $property)
    {
        $this->authorizeOwner($request, $property);

        $leadsByDate = Lead::where('property_id', $property->id)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($row) => ['date' => $row->date, 'count' => (int) $row->count]);

        return ApiResponse::success([
            // No per-day view log exists yet, so `views` is reported as a single total-to-date
            // point rather than a real time series (documented simplification).
            'views' => [['date' => Carbon::today()->format('Y-m-d'), 'count' => $property->views]],
            'leads' => $leadsByDate,
            'totalViews' => $property->views,
            'totalLeads' => Lead::where('property_id', $property->id)->count(),
        ]);
    }

    public function leads(Request $request)
    {
        $listingIds = $request->user()->listings()->pluck('id');

        $leads = Lead::whereIn('property_id', $listingIds)
            ->with(['property', 'buyer'])
            ->latest()
            ->paginate((int) $request->input('limit', 10));

        return ApiResponse::success(ApiResponse::paginated($leads, fn ($lead) => [
            'id' => 'lead_' . $lead->id,
            'property' => ['id' => 'prop_' . $lead->property->id, 'title' => $lead->property->title],
            'buyer' => [
                'name' => $lead->buyer->name,
                'phone' => $lead->buyer->phone ? $lead->buyer->country_code . $lead->buyer->phone : null,
                'avatar' => $lead->buyer->avatar,
            ],
            'message' => $lead->message,
            'createdAt' => $lead->created_at->toIso8601String(),
            'status' => $lead->status,
        ]));
    }

    public function businessProfile(Request $request)
    {
        $profile = $request->user()->businessProfile;

        return ApiResponse::success([
            'businessName' => $profile?->business_name,
            'gstNumber' => $profile?->gst_number,
            'address' => $profile?->address,
        ]);
    }

    public function updateBusinessProfile(Request $request)
    {
        $data = $request->validate([
            'businessName' => ['nullable', 'string', 'max:255'],
            'gstNumber' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $profile = BusinessProfile::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'business_name' => $data['businessName'] ?? null,
                'gst_number' => $data['gstNumber'] ?? null,
                'address' => $data['address'] ?? null,
            ]
        );

        return ApiResponse::success([
            'businessName' => $profile->business_name,
            'gstNumber' => $profile->gst_number,
            'address' => $profile->address,
        ]);
    }

    public function payoutSettings(Request $request)
    {
        $settings = $request->user()->payoutSettings;

        return ApiResponse::success([
            'bankAccountNumber' => $settings?->bank_account_number,
            'ifsc' => $settings?->ifsc,
            'upiId' => $settings?->upi_id,
        ]);
    }

    public function updatePayoutSettings(Request $request)
    {
        $data = $request->validate([
            'bankAccountNumber' => ['nullable', 'string', 'max:50'],
            'ifsc' => ['nullable', 'string', 'max:20'],
            'upiId' => ['nullable', 'string', 'max:100'],
        ]);

        $settings = PayoutSetting::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'bank_account_number' => $data['bankAccountNumber'] ?? null,
                'ifsc' => $data['ifsc'] ?? null,
                'upi_id' => $data['upiId'] ?? null,
            ]
        );

        return ApiResponse::success([
            'bankAccountNumber' => $settings->bank_account_number,
            'ifsc' => $settings->ifsc,
            'upiId' => $settings->upi_id,
        ]);
    }

    protected function authorizeOwner(Request $request, Property $property): void
    {
        abort_if($property->owner_id !== $request->user()->id, 403, 'You do not own this property.');
    }

    protected function validateListing(Request $request): array
    {
        return $request->validate([
            'propertyType' => ['required', 'string'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string'],
            'state' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'plotArea' => ['nullable', 'array'],
            'plotArea.min' => ['nullable', 'integer'],
            'plotArea.max' => ['nullable', 'integer'],
            'plotArea.unit' => ['nullable', 'string'],
            'plotSize' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'facing' => ['nullable', Rule::in(Property::FACING_OPTIONS)],
            'isCornerPlot' => ['nullable', 'boolean'],
            'propertyAge' => ['nullable', Rule::in(Property::PROPERTY_AGE_OPTIONS)],
            'possessionDate' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
            'highlights' => ['nullable', 'array'],
            'highlights.*' => ['string'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['string'],
            'nearbyPlaces' => ['nullable', 'array'],
            'nearbyPlaces.*.name' => ['required', 'string'],
            'nearbyPlaces.*.distanceKm' => ['required', 'numeric'],
            'documentIds' => ['nullable', 'array'],
            'documentIds.*' => ['string'],
            'imageUrls' => ['nullable', 'array'],
            'imageUrls.*' => ['string'],
            'status' => ['nullable', Rule::in(Property::STATUS_OPTIONS)],
        ]);
    }

    protected function fillListing(Property $property, array $data): void
    {
        $city = City::firstOrCreate(['name' => $data['city']]);
        $propertyType = PropertyType::firstOrCreate(['name' => $data['propertyType']]);
        $category = isset($data['category']) ? Category::firstOrCreate(['title' => $data['category']]) : null;

        $property->fill([
            'title' => $data['title'],
            'category_id' => $category?->id,
            'property_type_id' => $propertyType->id,
            'city_id' => $city->id,
            'location_line' => $data['location'] ?? null,
            'state' => $data['state'] ?? null,
            'pincode' => $data['pincode'] ?? null,
            'price_amount' => $data['price'],
            'plot_area_min' => $data['plotArea']['min'] ?? null,
            'plot_area_max' => $data['plotArea']['max'] ?? null,
            'plot_area_unit' => $data['plotArea']['unit'] ?? 'sq.ft',
            'plot_size' => $data['plotSize'] ?? null,
            'facing' => $data['facing'] ?? null,
            'is_corner_plot' => $data['isCornerPlot'] ?? false,
            'property_age' => $data['propertyAge'] ?? null,
            'possession_date' => $data['possessionDate'] ?? null,
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? $property->status ?? 'Active',
            'views' => $property->exists ? $property->views : 0,
        ]);
    }

    protected function syncListingRelations(Request $request, Property $property, array $data): void
    {
        if (isset($data['highlights'])) {
            $ids = collect($data['highlights'])->map(fn ($name) => Highlight::firstOrCreate(['name' => $name])->id);
            $property->highlights()->sync($ids);
        }

        if (isset($data['amenities'])) {
            $ids = collect($data['amenities'])->map(fn ($name) => Amenity::firstOrCreate(['name' => $name])->id);
            $property->amenities()->sync($ids);
        }

        if (isset($data['nearbyPlaces'])) {
            $property->nearbyPlaces()->delete();
            foreach ($data['nearbyPlaces'] as $place) {
                $property->nearbyPlaces()->create([
                    'name' => $place['name'],
                    'distance_km' => $place['distanceKm'],
                    'category' => $place['category'] ?? null,
                ]);
            }
        }

        $userId = $request->user()->id;

        if (! empty($data['imageUrls'])) {
            foreach ($data['imageUrls'] as $url) {
                $path = ltrim(parse_url($url, PHP_URL_PATH) ?? '', '/');
                $path = preg_replace('#^storage/#', '', $path);

                PropertyImage::where('owner_id', $userId)
                    ->whereNull('property_id')
                    ->where('path', $path)
                    ->update(['property_id' => $property->id]);
            }
        }

        if (! empty($data['documentIds'])) {
            $ids = collect($data['documentIds'])->map(fn ($id) => (int) str_replace('doc_', '', $id));

            PropertyDocument::where('owner_id', $userId)
                ->whereNull('property_id')
                ->whereIn('id', $ids)
                ->update(['property_id' => $property->id]);
        }
    }
}
