<?php

namespace App\Observers;

use App\Models\Good;
use App\Models\GoodAllocation;
use App\Models\GoodUnit;
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

        // ✅ Hapus kode unit terkait
        GoodUnit::where('good_allocation_id', $allocation->id)->delete();

        // ✅ Catat stock movement
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

    private function generateUnitCodes(GoodAllocation $allocation): void
    {
        $good     = $allocation->good;
        $location = $allocation->location;

        // ✅ Cek lokasi sudah punya kode ruang
        if (!$location->room_code) return;

        // ✅ Hitung no urut terakhir untuk kombinasi jenis barang + lokasi
        $lastSequence = GoodUnit::where('good_id', $good->id)
            ->where('location_id', $location->id)
            ->count();

        for ($i = 1; $i <= $allocation->quantity; $i++) {
            $sequence = $lastSequence + $i;
            $code     = GoodUnit::generateCode($good, $location, $sequence);

            GoodUnit::create([
                'good_id'            => $good->id,
                'good_allocation_id' => $allocation->id,
                'location_id'        => $location->id,
                'code'               => $code,
            ]);
        }
    }
}
