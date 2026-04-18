<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestNotificationLog extends Model
{
    protected $fillable = [
        'request_id',
        'threshold_days',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
