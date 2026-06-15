<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodUnit extends Model
{
    protected $fillable = [
        'good_id',
        'good_allocation_id',
        'location_id',
        'code',
    ];

    public function good()
    {
        return $this->belongsTo(Good::class);
    }

    public function allocation()
    {
        return $this->belongsTo(GoodAllocation::class, 'good_allocation_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // ✅ Helper generate kode
    public static function generateCode(Good $good, Location $location, int $sequence): string
    {
        $year = $good->procurement_year ?? now()->year;

        return sprintf(
            '%s-%s-%02d-%03d',
            $good->code,
            $location->room_code ?? 'UMUM',
            $year % 100,
            $sequence
        );
    }
}
