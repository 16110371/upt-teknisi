<?php

namespace App\Observers;

use App\Models\InfrastructureLog;
use App\Models\Request;

class RequestObserver
{
    public function updated(Request $request): void
    {
        if (!$request->wasChanged('status')) return;

        $oldStatus = $request->getOriginal('status');
        $newStatus = $request->status;
        $quantity  = $request->damaged_quantity ?? 1;

        // Pending → Dikerjakan
        if ($oldStatus === 'Pending' && $newStatus === 'Dikerjakan') {
            if (!$request->handled_at) {
                $request->updateQuietly(['handled_at' => now()]);
            }

            if ($request->infrastructure_id) {
                $infra = $request->infrastructure;
                $infra->update([
                    'good'   => max(0, $infra->good - $quantity),
                    'broken' => $infra->broken + $quantity,
                ]);
                $this->log($infra->id, $request->id, 'rusak', $quantity, 'Dikerjakan dari Pending');
            }
        }

        // Pending → Menunggu Part
        if ($oldStatus === 'Pending' && $newStatus === 'Menunggu Part') {
            if (!$request->handled_at) {
                $request->updateQuietly(['handled_at' => now()]);
            }

            if ($request->infrastructure_id) {
                $infra = $request->infrastructure;
                $infra->update([
                    'good'   => max(0, $infra->good - $quantity),
                    'broken' => $infra->broken + $quantity,
                ]);
                $this->log($infra->id, $request->id, 'rusak', $quantity, 'Menunggu Part dari Pending');
            }
        }

        // Pending → Selesai langsung
        if ($oldStatus === 'Pending' && $newStatus === 'Selesai') {
            $request->updateQuietly([
                'handled_at'   => now(),
                'completed_at' => now(),
            ]);

            if ($request->infrastructure_id) {
                $infra = $request->infrastructure;
                // good & broken tidak berubah karena langsung selesai
                $this->log($infra->id, $request->id, 'selesai', $quantity, 'Langsung selesai dari Pending');
            }
        }

        // Dikerjakan → Menunggu Part
        if ($oldStatus === 'Dikerjakan' && $newStatus === 'Menunggu Part') {
            // good & broken tidak berubah (sudah berkurang saat Dikerjakan)
            if ($request->infrastructure_id) {
                $this->log($request->infrastructure_id, $request->id, 'rusak', $quantity, 'Menunggu Part');
            }
        }

        // Menunggu Part → Dikerjakan
        if ($oldStatus === 'Menunggu Part' && $newStatus === 'Dikerjakan') {
            // good & broken tidak berubah
            if ($request->infrastructure_id) {
                $this->log($request->infrastructure_id, $request->id, 'rusak', $quantity, 'Dilanjutkan dari Menunggu Part');
            }
        }

        // Dikerjakan → Selesai
        // Menunggu Part → Selesai
        if (in_array($oldStatus, ['Dikerjakan', 'Menunggu Part']) && $newStatus === 'Selesai') {
            $request->updateQuietly(['completed_at' => now()]);

            if ($request->infrastructure_id) {
                $infra = $request->infrastructure;
                $infra->update([
                    'good'   => $infra->good + $quantity,
                    'broken' => max(0, $infra->broken - $quantity),
                ]);
                $this->log($infra->id, $request->id, 'selesai', $quantity, 'Selesai diperbaiki');
            }
        }

        // Selesai → Dikerjakan (dibuka kembali)
        if ($oldStatus === 'Selesai' && $newStatus === 'Dikerjakan') {
            $request->updateQuietly(['completed_at' => null]);

            if ($request->infrastructure_id) {
                $infra = $request->infrastructure;
                $infra->update([
                    'good'   => max(0, $infra->good - $quantity),
                    'broken' => $infra->broken + $quantity,
                ]);
                $this->log($infra->id, $request->id, 'rusak', $quantity, 'Dibuka kembali dari Selesai');
            }
        }

        // Dikerjakan/Menunggu Part → Tidak Diperbaiki (rusak permanen)
        if (in_array($oldStatus, ['Dikerjakan', 'Menunggu Part', 'Pending']) && $newStatus === 'Tidak Diperbaiki') {
            $request->updateQuietly(['completed_at' => now()]);

            if ($request->infrastructure_id) {
                $infra = $request->infrastructure;

                // Kalau dari Pending, good belum berkurang → kurangi dulu
                if ($oldStatus === 'Pending') {
                    $infra->update([
                        'good'             => max(0, $infra->good - $quantity),
                        'broken'           => $infra->broken,
                        'permanent_broken' => $infra->permanent_broken + $quantity,
                    ]);
                } else {
                    // Dari Dikerjakan/Menunggu Part, good sudah berkurang
                    // pindahkan dari broken ke permanent_broken
                    $infra->update([
                        'broken'           => max(0, $infra->broken - $quantity),
                        'permanent_broken' => $infra->permanent_broken + $quantity,
                    ]);
                }

                $this->log($infra->id, $request->id, 'manual', $quantity, 'Tidak dapat diperbaiki');
            }
        }
    }

    // Request dihapus
    public function deleted(Request $request): void
    {
        if (!$request->infrastructure_id) return;

        $infra    = $request->infrastructure;
        $quantity = $request->damaged_quantity ?? 1;

        if (in_array($request->status, ['Dikerjakan', 'Menunggu Part'])) {
            $infra->update([
                'good'   => $infra->good + $quantity,
                'broken' => max(0, $infra->broken - $quantity),
            ]);

            $this->log($infra->id, null, 'manual', $quantity, 'Request #' . $request->id . ' dihapus');
        }
    }

    // Helper log
    private function log($infraId, $requestId, $type, $quantity, $note): void
    {
        InfrastructureLog::create([
            'infrastructure_id' => $infraId,
            'request_id'        => $requestId,
            'type'              => $type,
            'quantity'          => $quantity,
            'note'              => $note,
        ]);
    }
}
