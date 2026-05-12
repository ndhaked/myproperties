<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     * @var string
     */

    protected $table = 'galleries';

    protected $appends = ['status_label'];

    public const STATUS_LABELS = [
        'draft'         => 'Draft',
        'for_review'    => 'Under Review',
        'approved'      => 'Approved',
        'new'           => 'New',
        'rejected'      => 'Rejected',
        'archived'      => 'Archived',
        'active'        => 'Active',
        'not_active'    => 'Not Active',
    ];

    /**
     * The primary key is the default 'id' and is an auto-incrementing integer (bigIncrements).
     * No need to define $primaryKey, $incrementing, or $keyType unless they deviate from defaults.
     */

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'gallery_name',
        'year_founded',
        'about_gallery',
        'logo_file_id',
        'license_file_id',
        'banner_image_id',
        'phone_number',
        'country_code',
        'invitation_id',
        'status',
        'reject_reason',
        'approved_at',
        'rejected_at',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array<string, string>
     */
    protected $casts = [
        // Ensure the UUID fields are treated as strings
        'logo_file_id' => 'string',
        'banner_image_id' => 'string',
        'invitation_id' => 'string',
        'year_founded' => 'string', // Stored as VARCHAR(4), so cast as string
    ];

    // --- Relationships ---

    /**
     * A Gallery has many branches. (One-to-Many)
     */
    public function branches(): HasMany
    {
        return $this->hasMany(GalleryBranch::class, 'gallery_id', 'id')->orderBy('position', 'asc');;
    }
    public function primaryBranch(): HasOne
    {
        return $this->hasOne(GalleryBranch::class, 'gallery_id', 'id')
            ->where('position', 1);
    }
    public function invitations()
    {
        return $this->belongsTo(Invitation::class, 'invitation_id', 'invitation_id');
    }
    public function artists()
    {
        return $this->hasMany(Artist::class, 'user_id', 'user_id');
    }
    public function artworks()
    {
        return $this->hasMany(Artwork::class, 'gallery_id');
    }
    /**
     * A Gallery has many users through the 'org_user' pivot table. (Many-to-Many with extra fields)
     * * We use a simpler belongsToMany() with the 'OrgUser' model as the pivot
     * for easier querying, rather than using the intermediate model directly.
     */
    public function users(): BelongsToMany
    {
        // Define the Many-to-Many relationship using the custom intermediate table ('org_user')
        return $this->belongsToMany(User::class, 'org_user', 'gallery_id', 'user_id')
            ->using(OrgUser::class) // Specify the custom pivot model
            ->withPivot(['org_user_id', 'title', 'created_at', 'updated_at']); // Include extra pivot columns
    }

    public function user(): BelongsTo
    {
        // Assumes you have a 'user_id' column in your artists table
        return $this->belongsTo(User::class);
    }

    /**
     * The gallery has one logo file. (One-to-One, technically a BelongsTo if the FK is on this side)
     * Assumes an 'AssetFile' model exists for the logo_file_id.
     */
    public function logoFile(): BelongsTo
    {
        return $this->belongsTo(AssetFile::class, 'logo_file_id', 'id');
    }



    public function coverFile(): BelongsTo
    {
        return $this->belongsTo(AssetFile::class, 'banner_image_id', 'id');
    }

    public function getLogoImageUrlAttribute()
    {
        // If logo exists in asset_files table
        if ($this->logoFile) {
            $baseUrl = config('filesystems.disks.azure.base_url');
            // Full relative path: e.g. gallery/logos/abc.jpeg
            $path = $this->logoFile->folder . '/' . $this->logoFile->filename;
            return $baseUrl .  $path;
        }

        // Fallback placeholder if no logo uploaded
        //$name = urlencode($this->gallery_name ?? 'Gallery');
        return asset('assets/images/placeholder_image.svg');
    }

    public function getCoverImageUrlAttribute()
    {
        // If logo exists in asset_files table
        if ($this->coverFile) {
            $baseUrl = config('filesystems.disks.azure.base_url');

            // Full relative path: e.g. gallery/logos/abc.jpeg
            $path = $this->coverFile->folder . '/' . $this->coverFile->filename;

            // Convert to full URL
            return $baseUrl .  $path;
        }

        // Fallback placeholder if no logo uploaded
        //$name = urlencode($this->gallery_name ?? 'Gallery');
        return asset('assets/images/placeholder_image.svg');
        // return "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
    }

    public function licenseFile(): BelongsTo
    {
        return $this->belongsTo(AssetFile::class, 'license_file_id');
    }

    public function getlicenseFileUrlAttribute()
    {
        // Check if the relationship exists (image is uploaded)
        if ($this->licenseFile) {
            $baseUrl = config('filesystems.disks.azure.base_url');
            $path = $this->licenseFile->file_key;
            return $baseUrl . $path;
        }
    }

    /**
     * The gallery belongs to an invitation. (One-to-One)
     * The gallery has invitation_id which references invitations.invitation_id
     */
    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class, 'invitation_id', 'invitation_id');
    }

    public function specialisation()
    {
        return $this->belongsTo(GallerySpecialisation::class, 'gallery_specialisation_id');
    }
    
    /**
     * Generate SEO-friendly slug from gallery name
     */
    public function getSlugAttribute()
    {
        return \Illuminate\Support\Str::slug($this->gallery_name);
    }
    
    public function getSpecialisationNamesAttribute()
    {
        if (!$this->gallery_specialisation_id) {
            return '';
        }

        $ids = explode(',', $this->gallery_specialisation_id);

        return GallerySpecialisation::whereIn('id', $ids)
            ->pluck('name')
            ->implode(', ');
    }

    public function images()
    {
          return $this->hasMany(GalleryImage::class)->latest()->limit(5);
    }

    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }
}
