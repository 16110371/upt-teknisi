<?php

namespace App\Observers;

use App\Models\GoodAllocation;
use App\Models\StockMovement;

class GoodAllocationObserver
{
    public function created(GoodAllocation $allocation): void
    {
        // ✅ Kurangi stok
        $allocation->good->decrement('stock', $allocation->quantity);

        // ✅ Catat pergerakan stok
        StockMovement::create([
            'good_id'        => $allocation->good_id,
            'type'           => 'alokasi',
            'quantity'       => $allocation->quantity,
            'user_id'        => $allocation->user_id,
            'reference_type' => GoodAllocation::class,
            'reference_id'   => $allocation->id,
            'note'           => 'Alokasi ke ' . $allocation->location->name,
        ]);
    }

    public function deleted(GoodAllocation $allocation): void
    {
        // ✅ Kembalikan stok
        $allocation->good->increment('stock', $allocation->quantity);

        StockMovement::create([
            'good_id'        => $allocation->good_id,
            'type'           => 'retur',
            'quantity'       => $allocation->quantity,
            'user_id'        => auth()->id() ?? $allocation->user_id,
            'reference_type' => GoodAllocation::class,
            'reference_id'   => $allocation->id,
            'note'           => 'Retur dari ' . $allocation->location->name,
        ]);
    }
}
