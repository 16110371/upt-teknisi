<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitLog extends Model
{
    protected $fillable = [
        'unit_id',
        'request_id',
        'type',
        'note',
    ];

    public function unit()
    {
        return $this->belongsTo(InfrastructureUnit::class, 'unit_id');
    }

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
