<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    public const FACING_OPTIONS = ['East', 'West', 'North', 'South'];

    public const PROPERTY_AGE_OPTIONS = ['New Property', '1-5 Years', '5-10 Years', '10+ Years'];

    public const STATUS_OPTIONS = ['Active', 'Inactive', 'Sold'];

    protected $fillable = [
        'title',
        'category_id',
        'property_type_id',
        'owner_id',
        'location_line',
        'city_id',
        'state',
        'pincode',
        'lat',
        'lng',
        'price_amount',
        'price_per_sqft',
        'plot_area_min',
        'plot_area_max',
        'plot_area_unit',
        'plot_size',
        'facing',
        'is_corner_plot',
        'property_age',
        'possession_date',
        'road_width',
        'description',
        'badge_label',
        'badge_color',
        'offer_text',
        'offer_highlight',
        'offer_bg_color',
        'offer_text_color',
        'status',
        'views',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'is_corner_plot' => 'boolean',
            'is_featured' => 'boolean',
            'possession_date' => 'date',
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
            'price_amount' => 'decimal:2',
            'price_per_sqft' => 'decimal:2',
            'plot_area_min' => 'integer',
            'plot_area_max' => 'integer',
            'views' => 'integer',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    public function documents()
    {
        return $this->hasMany(PropertyDocument::class);
    }

    public function nearbyPlaces()
    {
        return $this->hasMany(PropertyNearbyPlace::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenity');
    }

    public function highlights()
    {
        return $this->belongsToMany(Highlight::class, 'property_highlight');
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_properties')->withTimestamps();
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
