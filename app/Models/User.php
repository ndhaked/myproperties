<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use SoftDeletes, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'pending',
        'gallery_name',
        'country_id',
        'city',
        'address',
        'country_code',
        'phone_number',
        'external_links',
        'interest_reference',
        'interest_status',
        'interest_submitted_at',
        'last_interest_attempt_at',
        'consent_at',
        'reset_otp',
        'reset_otp_expires_at',
        'profile_photo',
        'timezone',
        'status',
    ];

    protected $appends = ['status_label'];

    public const STATUS_LABELS = ['active' => 'Active', 'inactive' => 'In-Active'];

    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'pending' => 'boolean',
            'interest_status' => 'boolean',
            'external_links' => 'array',
            'interest_submitted_at' => 'datetime',
            'last_interest_attempt_at' => 'datetime',
            'consent_at' => 'datetime',
        ];
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the profile photo URL.
     *
     * @return string|null
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (!$this->profile_photo) {
            return null;
        }

        // Use local/public storage URL
        // Ensure the path doesn't have leading slashes
        $path = ltrim($this->profile_photo, '/');

        // Use asset() helper for relative URLs to avoid APP_URL conflicts
        $baseUrl = config('filesystems.disks.azure.base_url');
        return $this->profile_photo ? $baseUrl . $this->profile_photo : null;
        
        return asset('storage/' . $path);
    }

    public function gallery()
    {
        return $this->hasOne(Gallery::class,'user_id');
    }

    public function activeGallery()
    {
        return $this->hasOne(Gallery::class,'user_id')->whereIn('status',['approved','for_review']);
    }

    public function finalActiveGallery()
    {
        return $this->hasOne(Gallery::class,'user_id')->whereIn('status',['approved']);
    }
}
