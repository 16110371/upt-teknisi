<?php

namespace App\Console\Commands;

use App\Models\Request;
use App\Models\RequestNotificationLog;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;

class CheckStaleRequests extends Command
{
    protected $signature   = 'requests:check-stale';
    protected $description = 'Kirim notifikasi untuk permintaan yang belum berubah status';

    public function handle()
    {
        $activeStatuses = ['Pending', 'Dikerjakan', 'Menunggu Part'];
        $requests       = Request::whereIn('status', $activeStatuses)->get();
        $users          = User::all();

        foreach ($requests as $request) {
            $daysSinceUpdate = (int) $request->updated_at->diffInDays(now());
            $threshold       = $this->getThreshold($daysSinceUpdate);

            if (!$threshold) continue;

            // ✅ Cek apakah threshold ini sudah pernah dikirim
            $alreadyNotified = RequestNotificationLog::where('request_id', $request->id)
                ->where('threshold_days', $threshold)
                ->exists();

            if ($alreadyNotified) continue;

            // ✅ Kirim notifikasi ke semua user
            $title = "⚠️ Permintaan Belum Ditangani {$threshold} Hari";
            $body  = "Permintaan #" . str_pad($request->id, 3, '0', STR_PAD_LEFT) .
                " dari {$request->requester_name} belum berubah status selama {$daysSinceUpdate} hari";

            foreach ($users as $user) {
                Notification::make()
                    ->title($title)
                    ->body($body)
                    ->icon('heroicon-o-clock')
                    ->warning()
                    ->sendToDatabase($user);
            }

            // ✅ Simpan log notif sudah dikirim
            RequestNotificationLog::create([
                'request_id'     => $request->id,
                'threshold_days' => $threshold,
                'sent_at'        => now(),
            ]);

            $this->info("Notif dikirim: Request #{$request->id} - {$threshold} hari");
        }

        $this->info('Selesai cek permintaan stale.');
    }

    private function getThreshold(int $days): ?int
    {
        // Threshold: 7 hari pertama
        if ($days >= 7 && $days < 30) {
            return 7;
        }

        // Threshold: 30, 60, 90, 120, dst (kelipatan 30)
        if ($days >= 30) {
            return (int) (floor($days / 30) * 30);
        }

        return null;
    }
}
