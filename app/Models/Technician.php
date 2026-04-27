<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'position',
    ];

    public function requests()
    {
        return $this->belongsToMany(Request::class, 'request_technician');
    }
}
