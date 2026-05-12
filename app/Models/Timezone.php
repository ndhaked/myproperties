<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timezone extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'timezone',
        'name',
        'abbreviation',
        'offset',
        'offset_seconds',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'offset_seconds' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get active timezones ordered by sort order
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActive()
    {
        return static::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get timezone by identifier
     *
     * @param string $timezone
     * @return Timezone|null
     */
    public static function findByIdentifier(string $timezone)
    {
        return static::where('timezone', $timezone)->first();
    }
}
