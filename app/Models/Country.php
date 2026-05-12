<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'country_of_residence',
        'country_of_birth',
        'nationality',
        'regional_affiliation_country',
        'country',
        'country_code',
    ];
}
