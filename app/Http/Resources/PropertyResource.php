<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PropertyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $request->user();

        return [
            'id' => 'prop_' . $this->id,
            'title' => $this->title,
            'propertyType' => $this->propertyType?->name,
            'category' => $this->category?->title,
            'location' => [
                'line' => $this->location_line,
                'city' => $this->city?->name,
                'state' => $this->state,
                'pincode' => $this->pincode,
                'lat' => $this->lat !== null ? (float) $this->lat : null,
                'lng' => $this->lng !== null ? (float) $this->lng : null,
            ],
            'price' => [
                'amount' => (float) $this->price_amount,
                'display' => $this->formatPrice((float) $this->price_amount),
                'perSqftDisplay' => $this->price_per_sqft
                    ? '₹' . number_format((float) $this->price_per_sqft) . ' per sq.ft'
                    : null,
            ],
            'plotArea' => [
                'min' => $this->plot_area_min,
                'max' => $this->plot_area_max,
                'unit' => $this->plot_area_unit,
            ],
            'plotSize' => $this->plot_size,
            'facing' => $this->facing,
            'isCornerPlot' => (bool) $this->is_corner_plot,
            'propertyAge' => $this->property_age,
            'possessionDate' => $this->possession_date?->format('Y-m-d'),
            'roadWidth' => $this->road_width,
            'badge' => $this->badge_label ? [
                'label' => $this->badge_label,
                'color' => $this->badge_color,
            ] : null,
            'offer' => $this->offer_text ? [
                'text' => $this->offer_text,
                'highlight' => $this->offer_highlight,
                'bgColor' => $this->offer_bg_color,
                'textColor' => $this->offer_text_color,
            ] : null,
            'images' => $this->images->map(fn ($image) => Storage::url($image->path))->values(),
            'description' => $this->description,
            'highlights' => $this->highlights->pluck('name')->values(),
            'amenities' => $this->amenities->map(fn ($amenity) => [
                'name' => $amenity->name,
                'icon' => $amenity->icon,
            ])->values(),
            'nearbyPlaces' => $this->nearbyPlaces->map(fn ($place) => [
                'name' => $place->name,
                'distanceKm' => (float) $place->distance_km,
                'category' => $place->category,
            ])->values(),
            'documents' => $this->documents->map(fn ($document) => [
                'id' => 'doc_' . $document->id,
                'name' => $document->name,
                'type' => $document->type,
                'sizeMb' => (float) $document->size_mb,
                'url' => Storage::url($document->path),
            ])->values(),
            'views' => $this->views,
            'status' => $this->status,
            'isFeatured' => (bool) $this->is_featured,
            'isSaved' => $this->resolveIsSaved($user),
            'owner' => $this->owner ? [
                'id' => 'user_' . $this->owner->id,
                'name' => $this->owner->name,
                'avatar' => $this->owner->avatar ? Storage::url($this->owner->avatar) : null,
                'memberSince' => $this->owner->created_at?->format('Y-m-d'),
                'phone' => $this->owner->country_code . $this->owner->phone,
            ] : null,
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }

    protected function resolveIsSaved($user): bool
    {
        if (! $user) {
            return false;
        }

        // Controllers may preload `savedPropertyIds` on the request to avoid an N+1 query per item.
        $preloaded = request()->attributes->get('savedPropertyIds');

        if (is_array($preloaded)) {
            return in_array($this->id, $preloaded, true);
        }

        return $user->savedProperties()->where('property_id', $this->id)->exists();
    }

    protected function formatPrice(float $amount): string
    {
        if ($amount >= 10000000) {
            return '₹' . round($amount / 10000000, 2) . ' Cr*';
        }

        if ($amount >= 100000) {
            return '₹' . round($amount / 100000, 2) . ' L*';
        }

        return '₹' . number_format($amount);
    }
}
