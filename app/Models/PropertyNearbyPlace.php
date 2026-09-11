<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyNearbyPlace extends Model
{
    protected $table = 'property_nearby_places';

    protected $fillable = [
        'property_id',
        'name',
        'distance_km',
        'category',
    ];

    protected function casts(): array
    {
        return [
            'distance_km' => 'decimal:2',
        ];
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
