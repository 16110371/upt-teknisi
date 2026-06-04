<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestUnit extends Model
{
    protected $fillable = [
        'request_id',
        'unit_id',
        'type',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function unit()
    {
        return $this->belongsTo(InfrastructureUnit::class, 'unit_id');
    }
}
