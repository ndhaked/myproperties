<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Highlight extends Model
{
    protected $fillable = [
        'name',
    ];

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_highlight');
    }
}
