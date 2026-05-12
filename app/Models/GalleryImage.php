<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use HasFactory;

    protected $table = 'gallery_images';

    protected $fillable = [
        'gallery_id',
        'asset_file_id',
        'is_featured',
    ];

    /**
     * Each image belongs to one gallery
     */
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    /**
     * If needed, relate image to asset file table
     * Example: asset_files table
     */
    public function asset()
    {
        return $this->belongsTo(AssetFile::class, 'asset_file_id');
    }

    public function getGallerySpaceUrlAttribute()
    {
        // If logo exists in asset_files table
        if ($this->asset) {
            $baseUrl = config('filesystems.disks.azure.base_url');

            // Full relative path: e.g. gallery/logos/abc.jpeg
            $path = $this->asset->folder . '/' . $this->asset->filename;

            // Convert to full URL
            return $baseUrl .  $path;
        }

        // Fallback placeholder if no logo uploaded
        //$name = urlencode($this->gallery_name ?? 'Gallery');
         return asset('assets/images/placeholder_image.svg');
        // return "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
    }
}
