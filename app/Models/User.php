<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected string $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'country_code',
        'role',
        'avatar',
        'is_verified',
        'notifications_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'is_verified' => 'boolean',
            'notifications_enabled' => 'boolean',
        ];
    }

    public function listings()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    public function savedProperties()
    {
        return $this->belongsToMany(Property::class, 'saved_properties')->withTimestamps();
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'buyer_id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'buyer_id');
    }

    public function appNotifications()
    {
        return $this->hasMany(AppNotification::class);
    }

    public function businessProfile()
    {
        return $this->hasOne(BusinessProfile::class);
    }

    public function payoutSettings()
    {
        return $this->hasOne(PayoutSetting::class);
    }

    protected static function booted(): void
    {
        // Keeps spatie's model_has_roles in sync with the plain `role` column (Buyer/Seller)
        // automatically, wherever that column is set — registration, social login, admin
        // edits, etc. — without every call site needing to remember to assignRole().
        static::saved(function (User $user) {
            if ($user->role && ($user->wasRecentlyCreated || $user->wasChanged('role'))) {
                $user->syncRoles([$user->role]);
            }
        });
    }
}
