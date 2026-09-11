<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    public const STATUS_OPTIONS = ['New', 'Contacted', 'Closed'];

    protected $fillable = [
        'property_id',
        'buyer_id',
        'message',
        'status',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
