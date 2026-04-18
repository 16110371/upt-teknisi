<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infrastructure extends Model
{
    protected $fillable = [
        'location_id',
        'category_id',
        'name',
        'total',
        'good',
        'broken',
        'permanent_broken',
        'note',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function logs()
    {
        return $this->hasMany(InfrastructureLog::class);
    }
}
