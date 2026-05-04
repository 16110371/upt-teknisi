<?php

namespace App\Observers;

use App\Models\InfrastructureLog;
use App\Models\Request;
use Illuminate\Support\Facades\Log;

class RequestObserver
{
    public function updated(Request $request): void
    {
        Log::info('Observer triggered', [
            'status_changed' => $request->wasChanged('status'),
            'old_status'     => $request->getOriginal('status'),
            'new_status'     => $request->status,
            'fixed'          => $request->fixed_quantity,
            'permanent'      => $request->permanent_quantity,
            'damaged'        => $request->damaged_quantity,
        ]);
        if (
            !$request->wasChanged('status') &&
            !$request->wasChanged('fixed_quantity') &&
            !$request->wasChanged('permanent_quantity')
        ) return;

        $oldStatus = $request->getOriginal('status');
        $newStatus = $request->status;
        $damaged   = $request->damaged_quantity ?? 1;
        $fixed     = $request->fixed_quantity ?? 0;
        $permanent = $request->permanent_quantity ?? 0;
        $sisa      = max(0, $damaged - $fixed - $permanent);

        // ✅ Pending → Dikerjakan
        if (
            $request->wasChanged('status') &&
            $oldStatus === 'Pending' && $newStatus === 'Dikerjakan'
        ) {
            if (!$request->handled_at) {
                $request->updateQuietly(['handled_at' => now()]);
            }

            if ($request->infrastructure_id) {
                $infra = $request->infrastructure;
                if (!$request->from_room_check) {
                    $infra->update([
                        'good'   => max(0, $infra->good - $damaged),
                        'broken' => $infra->broken + $damaged,
                    ]);
                }
                $this->log($infra->id, $request->id, 'rusak', $damaged, 'Dikerjakan dari Pending');
            }
        }

        // ✅ Pending → Menunggu Part
        if (
            $request->wasChanged('status') &&
            $oldStatus === 'Pending' && $newStatus === 'Menunggu Part'
        ) {
            if (!$request->handled_at) {
                $request->updateQuietly(['handled_at' => now()]);
            }

            if ($request->infrastructure_id) {
                if (!$request->from_room_check) {
                    $infra->update([
                        'good'   => max(0, $infra->good - $damaged),
                        'broken' => $infra->broken + $damaged,
                    ]);
                }
                $this->log($infra->id, $request->id, 'rusak', $damaged, 'Menunggu Part dari Pending');
            }
        }

        // ✅ Pending → Selesai langsung
        if (
            $request->wasChanged('status') &&
            $oldStatus === 'Pending' && $newStatus === 'Selesai'
        ) {
            $request->updateQuietly([
                'handled_at'   => now(),
                'completed_at' => now(),
            ]);

            if ($request->infrastructure_id) {
                $infra = $request->infrastructure;
                if ($request->from_room_check) {
                    // ✅ Dari room check: good + fixed, broken - damaged, permanent + permanent
                    $infra->update([
                        'good'             => $infra->good + $fixed,
                        'broken'           => max(0, $infra->broken - $damaged + ($damaged - $fixed - $permanent)),
                        'permanent_broken' => $infra->permanent_broken + $permanent,
                    ]);
                } else {
                    $infra->update([
                        'good'             => max(0, $infra->good - $damaged + $fixed),
                        'broken'           => max(0, $infra->broken),
                        'permanent_broken' => $infra->permanent_broken + $permanent,
                    ]);
                }
                $this->log($infra->id, $request->id, 'selesai', $fixed, 'Langsung selesai dari Pending');
            }
        }

        // ✅ Dikerjakan → Menunggu Part
        if (
            $request->wasChanged('status') &&
            $oldStatus === 'Dikerjakan' && $newStatus === 'Menunggu Part'
        ) {
            if ($request->infrastructure_id) {
                $this->log($request->infrastructure_id, $request->id, 'rusak', $damaged, 'Menunggu Part');
            }
        }

        // ✅ Menunggu Part → Dikerjakan
        if (
            $request->wasChanged('status') &&
            $oldStatus === 'Menunggu Part' && $newStatus === 'Dikerjakan'
        ) {
            if ($request->infrastructure_id) {
                $this->log($request->infrastructure_id, $request->id, 'rusak', $damaged, 'Dilanjutkan dari Menunggu Part');
            }
        }

        // ✅ Dikerjakan/Menunggu Part → Selesai
        if (
            $request->wasChanged('status') &&
            in_array($oldStatus, ['Dikerjakan', 'Menunggu Part']) &&
            $newStatus === 'Selesai'
        ) {
            $request->updateQuietly(['completed_at' => now()]);

            if ($request->infrastructure_id) {
                $infra = $request->infrastructure;

                // ✅ good + fixed, broken - damaged, permanent_broken + permanent
                $infra->update([
                    'good'             => $infra->good + $fixed,
                    'broken'           => max(0, $infra->broken - $damaged + $sisa),
                    'permanent_broken' => $infra->permanent_broken + $permanent,
                ]);

                $this->log(
                    $infra->id,
                    $request->id,
                    'selesai',
                    $fixed,
                    "Selesai: fixed={$fixed}, permanent={$permanent}, sisa={$sisa}"
                );
            }
        }

        // ✅ Selesai → Dikerjakan (dibuka kembali)
        if (
            $request->wasChanged('status') &&
            $oldStatus === 'Selesai' && $newStatus === 'Dikerjakan'
        ) {
            $request->updateQuietly(['completed_at' => null]);

            if ($request->infrastructure_id) {
                $infra = $request->infrastructure;
                $infra->update([
                    'good'             => max(0, $infra->good - $fixed),
                    'broken'           => $infra->broken + $damaged - $permanent,
                    'permanent_broken' => max(0, $infra->permanent_broken - $permanent),
                ]);
                $this->log($infra->id, $request->id, 'rusak', $damaged, 'Dibuka kembali dari Selesai');
            }
        }

        // ✅ Dikerjakan/Menunggu Part/Pending → Tidak Diperbaiki
        if (
            $request->wasChanged('status') &&
            in_array($oldStatus, ['Dikerjakan', 'Menunggu Part', 'Pending']) &&
            $newStatus === 'Tidak Diperbaiki'
        ) {
            $request->updateQuietly(['completed_at' => now()]);

            if ($request->infrastructure_id) {
                $infra = $request->infrastructure;

                if ($oldStatus === 'Pending') {
                    $infra->update([
                        'good'             => max(0, $infra->good - $damaged),
                        'permanent_broken' => $infra->permanent_broken + $damaged,
                    ]);
                } else {
                    $infra->update([
                        'broken'           => max(0, $infra->broken - $damaged),
                        'permanent_broken' => $infra->permanent_broken + $damaged,
                    ]);
                }

                $this->log($infra->id, $request->id, 'manual', $damaged, 'Tidak dapat diperbaiki');
            }
        }

        // ✅ Update fixed/permanent quantity tanpa ubah status
        if (
            !$request->wasChanged('status') &&
            ($request->wasChanged('fixed_quantity') || $request->wasChanged('permanent_quantity'))
        ) {
            if ($request->infrastructure_id) {
                $infra        = $request->infrastructure;
                $oldFixed     = $request->getOriginal('fixed_quantity') ?? 0;
                $oldPermanent = $request->getOriginal('permanent_quantity') ?? 0;
                $diffFixed     = $fixed - $oldFixed;
                $diffPermanent = $permanent - $oldPermanent;

                $infra->update([
                    'good'             => max(0, $infra->good + $diffFixed),
                    'broken'           => max(0, $infra->broken - $diffFixed - $diffPermanent),
                    'permanent_broken' => max(0, $infra->permanent_broken + $diffPermanent),
                ]);

                $this->log(
                    $infra->id,
                    $request->id,
                    'manual',
                    $fixed,
                    "Update: fixed={$fixed}, permanent={$permanent}"
                );
            }
        }
    }

    public function deleted(Request $request): void
    {
        if (!$request->infrastructure_id) return;

        $infra    = $request->infrastructure;
        $damaged  = $request->damaged_quantity ?? 1;

        if (in_array($request->status, ['Dikerjakan', 'Menunggu Part'])) {
            $infra->update([
                'good'   => $infra->good + $damaged,
                'broken' => max(0, $infra->broken - $damaged),
            ]);
            $this->log($infra->id, null, 'manual', $damaged, 'Request #' . $request->id . ' dihapus');
        }
    }

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
