<?php

namespace App\Observers;

use App\Models\Infrastructure;
use App\Models\InfrastructureUnit;

class InfrastructureObserver
{
    public function created(Infrastructure $infrastructure): void
    {
        $this->generateUnits($infrastructure, 0, $infrastructure->total);
    }

    public function updated(Infrastructure $infrastructure): void
    {
        if (!$infrastructure->wasChanged('total')) return;

        $oldTotal     = $infrastructure->getOriginal('total');
        $newTotal     = $infrastructure->total;
        $currentCount = $infrastructure->units()->count();

        if ($newTotal > $oldTotal) {
            // ✅ Tambah unit baru
            $this->generateUnits($infrastructure, $currentCount, $newTotal - $oldTotal);
        } elseif ($newTotal < $oldTotal) {
            // ✅ Nonaktifkan unit yang lebih dari total baru
            $infrastructure->units()
                ->where('status', 'good')
                ->orderBy('id', 'desc')
                ->limit($oldTotal - $newTotal)
                ->update(['is_active' => false]);
        }
    }

    private function generateUnits(Infrastructure $infrastructure, int $startFrom, int $count): void
    {
        $infrastructure->load(['category', 'location']);

        for ($i = 1; $i <= $count; $i++) {
            $number = $startFrom + $i;
            $code   = InfrastructureUnit::generateCode($infrastructure, $number);

            // ✅ Cek kalau kode sudah ada, tambah suffix
            $originalCode = $code;
            $suffix       = 1;
            while (InfrastructureUnit::where('code', $code)->exists()) {
                $code = $originalCode . '-' . $suffix;
                $suffix++;
            }

            InfrastructureUnit::create([
                'infrastructure_id' => $infrastructure->id,
                'code'              => $code,
                'status'            => 'good',
                'is_active'         => true,
            ]);
        }
    }
}
