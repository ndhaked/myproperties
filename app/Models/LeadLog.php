<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadLog extends Model
{

    protected $fillable = [
        'lead_id', 'user_id', 'log_type', 'log_date', 'log_time', 
        'description', 'duration', 'outcome', 'status', 'lead_type'
    ];
    
    protected $casts = ['log_date' => 'date'];

    public function lead() { 
        return $this->belongsTo(Lead::class); 
    }
    
    public function user() {
         return $this->belongsTo(User::class); 
    }
}