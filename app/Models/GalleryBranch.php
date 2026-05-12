<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GalleryBranch extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'gallery_branch';

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
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'gallery_id',
        'branch_name',
        'address_line',
        'city',
        'country',
        'post_code',
        'position',
        'mobile_country_code',
        'mobile_number',
        'landline_country_code',
        'landline_number',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string', // Ensure UUID is treated as a string
    ];

    /**
     * Define the inverse relationship: A gallery branch belongs to one gallery.
     */
    public function gallery(): BelongsTo
    {
        // The branch belongs to the 'Gallery' model using the 'gallery_id' foreign key.
        return $this->belongsTo(Gallery::class, 'gallery_id', 'id');
    }
    public function countries()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }
}
