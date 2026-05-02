<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomCheck extends Model
{
    protected $fillable = [
        'location_id',
        'user_id',
        'note',
    ];

    protected $with = ['items', 'location', 'user'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(RoomCheckItem::class);
    }
}
