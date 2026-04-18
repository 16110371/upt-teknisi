<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfrastructureLog extends Model
{
    protected $fillable = [
        'infrastructure_id',
        'request_id',
        'type',
        'quantity',
        'note',
    ];

    public function infrastructure()
    {
        return $this->belongsTo(Infrastructure::class);
    }

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
