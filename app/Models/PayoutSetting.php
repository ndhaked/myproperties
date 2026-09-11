<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutSetting extends Model
{
    protected $fillable = [
        'user_id',
        'bank_account_number',
        'ifsc',
        'upi_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
