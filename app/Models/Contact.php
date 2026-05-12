<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Contact extends Model
{
    use HasUuids;   // ← UUID auto-handling

    public $incrementing = false;      
    protected $keyType = 'string';     

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'subject',
        'message',
        'country_id',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
