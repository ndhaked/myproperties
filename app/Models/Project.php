<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'slug',  // Added here
        'status'
    ];

    protected $appends = ['status_label'];

    public const STATUS_LABELS = [
        1     => 'Active',
        0     => 'In-Active'
    ];

    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }
}