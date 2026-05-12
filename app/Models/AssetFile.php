<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssetFile extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'asset_files';

    /**
     * The primary key for the model.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the primary key is auto-incrementing.
     * This is false because the primary key is a UUID.
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key ID.
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Boot function to automatically generate UUID when creating model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'artwork_id',
        'file_key',
        'filename',
        'folder',
        'mime_type',
        'width',
        'height',
        'size_bytes',
        'is_primary',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'artwork_id' => 'string',
        'width' => 'integer',
        'height' => 'integer',
        'size_bytes' => 'integer',
        'is_primary' => 'boolean',
        'deleted_at' => 'datetime', // Required by SoftDeletes
    ];

    // --- Relationships ---

    /**
     * The asset may belong to an Artwork. (One-to-Many inverse)
     * Links to artwork via artwork_id foreign key to artwork_id primary key.
     */
    public function artwork(): BelongsTo
    {
        // Artwork model uses 'artwork_id' as primary key (UUID)
        return $this->belongsTo(Artwork::class, 'artwork_id', 'artwork_id');
    }

    /**
     * The asset may be used as the logo for a Gallery. (One-to-One inverse)
     * This links back to the 'galleries' table where 'logo_file_id' references this file's 'id'.
     */
    public function logoForGallery(): HasOne
    {
        // Assumes a 'Gallery' model exists.
        // It uses 'logo_file_id' on the Gallery model to find the single Gallery record.
        return $this->hasOne(Gallery::class, 'logo_file_id', 'id');
    }
     public function getFullFilePathAttribute(): ?string
    {
        if (!$this->file_key) {
            return null;
        }

        // Use asset() helper for relative URLs to avoid APP_URL conflicts
      //  $baseUrl = config('filesystems.disks.azure.base_url');
        //return $this->file_key ? $baseUrl . $this->file_key : null;
return asset($this->file_key);
    }
}
