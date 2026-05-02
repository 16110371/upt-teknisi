<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomCheckItem extends Model
{
    protected $fillable = [
        'room_check_id',
        'infrastructure_id',
        'status',
        'note',
    ];

    public function roomCheck()
    {
        return $this->belongsTo(RoomCheck::class);
    }

    public function infrastructure()
    {
        return $this->belongsTo(Infrastructure::class);
    }
}
