<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdaiTutorial extends Model
{
    use HasFactory;

    protected $table = 'adai_tutorials';

    protected $fillable = [
        'title',
        'desc',
        'thumbnail_image',
        'video_id',
        'url',
        'status',
    ];
}