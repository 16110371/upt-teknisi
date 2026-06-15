<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodAllocation extends Model
{
    protected $fillable = [
        'good_id',
        'location_id',
        'user_id',
        'quantity',
        'allocation_date',
        'note',
    ];

    protected $casts = [
        'allocation_date' => 'date',
    ];

    public function good()
    {
        return $this->belongsTo(Good::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
