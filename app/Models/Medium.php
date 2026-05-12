<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medium extends Model
{
    use HasFactory;

    // "mediums" is the default table name, but if you want to be specific:
    protected $table = 'mediums';

    protected $fillable = [
        'name',
        'status'
    ];
}