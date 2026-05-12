<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CmsPage extends Model
{
    protected $fillable = [
        'page_title',
        'content',
        'section'
    ];
    

    /**
     * Get the full URL based on the ENABLE_CDN_FOR_CMS flag
     */
    public function getCmsImageUrl($path)
    {
        if (!$path) return '';

        // If it's already a full URL, don't change it
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Check the flag from .env
        $useCdn = env('ENABLE_CDN_FOR_CMS', false);

        if ($useCdn) {
            // Use the asset() helper which uses ASSET_URL
            return asset($path);
        }

        // Use the original server (APP_URL)
        return rtrim(config('app.url'), '/') . '/' . ltrim($path, '/');
    }
}