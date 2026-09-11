<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'gst_number',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
