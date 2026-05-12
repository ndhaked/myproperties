<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadTask extends Model
{
    use HasFactory;

    protected $table = 'lead_tasks';

    protected $fillable = [
        'lead_id',
        'user_id',
        'name',
        'date',
        'time',
        'description',
        'type',
        'status',
    ];

    // Ensure date/time are treated as Carbon instances
    protected $casts = [
        'date' => 'date',
        // 'time' => 'datetime:H:i', // Optional formatting
    ];

    /**
     * Relationship: Task belongs to a Lead
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    } 

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}