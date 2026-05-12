<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class NewsletterSubscription extends Model
{
   use HasUuids;   // ← UUID auto-handling

    public $incrementing = false;      
    protected $keyType = 'string';     

     protected $fillable = [
        'email',
    ];
   
}
