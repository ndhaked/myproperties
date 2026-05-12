<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'full_name',
        'email',
        'mobile',
        'country',
        'city',
        'campaign',
        'project_id',
        'budget',
        'source_id',
        'medium_id',
        'purpose_id',
        'purpose_type_id',
        'lead_category_id',
        'assigned_agent_id', // New
        'status',            // New
    ];

    protected $appends = ['status_label'];

    public const STATUS_LABELS = ['new' => 'New', 'contacted' => 'Contacted', 'qualified' => 'Qualified', 'lost' => 'Lost', 'closed' => 'Closed'];

    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    // --- Relationships ---

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function budget() {
        return $this->belongsTo(Budget::class);
    }

    public function source() {
        return $this->belongsTo(Source::class);
    }

    public function medium() {
        return $this->belongsTo(Medium::class);
    }

    public function purpose() {
        return $this->belongsTo(Purpose::class);
    }

    public function purposeType() {
        return $this->belongsTo(PurposeType::class);
    }

    public function leadCategory() {
        return $this->belongsTo(LeadCategory::class);
    }

    // Relationship to the User (Agent) model
    public function agent() {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    /**
     * Get all tasks associated with the lead.
     */
    public function tasks()
    {
        return $this->hasMany(LeadTask::class)->orderBy('id','desc');
    }

    public function notes()
    {
        return $this->hasMany(LeadNote::class)->orderBy('id','desc');
    }

    public function logs()
    {
        return $this->hasMany(LeadLog::class)->orderBy('id','desc');
    }

    public function callLogs()
    {
        return $this->hasMany(LeadLog::class)->where('log_type','call')->orderBy('id','desc');
    }

    public function emailLogs()
    {
        return $this->hasMany(LeadLog::class)->where('log_type','email')->orderBy('id','desc');
    }

    public function meetingLogs()
    {
        return $this->hasMany(LeadLog::class)->where('log_type','meeting')->orderBy('id','desc');
    }

    public function whatsappLogs()
    {
        return $this->hasMany(LeadLog::class)->where('log_type','whatsapp')->orderBy('id','desc');
    }


    public function activityLogs()
    {
        return $this->hasMany(LeadActivityLog::class, 'lead_id')
                    ->orderBy('created_at', 'desc');
    }
}