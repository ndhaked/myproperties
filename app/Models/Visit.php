<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    public const STATUS_OPTIONS = ['Upcoming', 'Completed', 'Cancelled'];

    protected $fillable = [
        'property_id',
        'buyer_id',
        'date',
        'time',
        'visitor_name',
        'visitor_phone',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
