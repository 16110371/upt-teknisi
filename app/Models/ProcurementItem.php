<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementItem extends Model
{
    protected $fillable = [
        'procurement_request_id',
        'name',
        'specification',
        'quantity',
        'unit',
        'estimated_price',
        'note',
    ];

    protected $casts = [
        'estimated_price' => 'decimal:2',
    ];

    public function procurementRequest()
    {
        return $this->belongsTo(ProcurementRequest::class);
    }

    public function good()
    {
        return $this->hasOne(Good::class);
    }

    // ✅ Total estimasi harga
    public function getTotalEstimatedPriceAttribute(): float
    {
        return $this->quantity * ($this->estimated_price ?? 0);
    }
}
