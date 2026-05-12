<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadNote extends Model
{
    use HasFactory;

    protected $table = 'lead_notes';

    protected $fillable = [
        'lead_id',
        'user_id',
        'description',
    ];

    /**
     * Relationship: Note belongs to a Lead
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Relationship: Note belongs to a User (Creator)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}