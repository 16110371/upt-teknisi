<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    protected $fillable = [
        'item_name',
        'purchase_url',
        'quantity',
        'estimated_price',
        'description',
        'status',
        'requested_at',
    ];
}
