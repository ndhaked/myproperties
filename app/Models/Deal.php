<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id', 'project_type_id', 'purpose_id', 'purpose_type_id',
        'developer_name', 'deal_date', 'invoice_no', 'source_id',
        'client_name', 'client_mobile_no', 'client_email',
        'price', 'commission_percentage', 'commission_amount',
        'vat_percentage', 'vat_amount', 'vat_paid', 'total_invoice',
        'down_payment_percentage', 'down_payment_amount', 'remaining_down_payment',
        'agent_id', 'agent_commission_percentage', 'agent_commission_amount',
        'leader_id', 'leader_commission_percentage', 'leader_commission_amount',
        'sales_director_id', 'sales_director_commission_percentage', 'sales_director_commission_amount',
        'deal_status_id', 'notes'
    ];

    // --- Relationships ---

    public function project() { return $this->belongsTo(Project::class); }
    public function projectType() { return $this->belongsTo(ProjectType::class); }
    public function purpose() { return $this->belongsTo(Purpose::class); }
    public function purposeType() { return $this->belongsTo(PurposeType::class); }
    public function source() { return $this->belongsTo(Source::class); }
    public function dealStatus() { return $this->belongsTo(DealStatus::class); }

    // Users (Agents/Staff)
    public function agent() { return $this->belongsTo(User::class, 'agent_id'); }
    public function leader() { return $this->belongsTo(User::class, 'leader_id'); }
    public function salesDirector() { return $this->belongsTo(User::class, 'sales_director_id'); }
}