<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadActivityLog extends Model
{
    use HasFactory;

    // Defines which table this model interacts with
    protected $table = 'lead_activity_logs';

    // Mass assignable attributes
    protected $fillable = [
        'lead_id',
        'user_id',          // Who performed the action (Admin/Agent)
        'assigned_user_id', // Who the lead was assigned TO (Nullable)
        'log_type',         // 'call', 'email', 'status_change', 'assignment'
        'description',
        'log_date',
        'log_time'
    ];

    /**
     * Relationship: The Lead this log belongs to.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Relationship: The User who PERFORMED the action.
     * (e.g., The Admin who clicked "Assign")
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: The User who was ASSIGNED.
     * (e.g., The Agent who received the lead)
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}