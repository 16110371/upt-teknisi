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
        'requested_by',
        'position',
        'location_id',
        'signed_document_photo',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
