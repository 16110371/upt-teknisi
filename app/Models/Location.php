<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'room_code',
    ];

    public function infrastructures()
    {
        return $this->hasMany(Infrastructure::class);
    }

    public function goodAllocations()
    {
        return $this->hasMany(GoodAllocation::class);
    }

    public function goodUnits()
    {
        return $this->hasMany(GoodUnit::class);
    }
}
