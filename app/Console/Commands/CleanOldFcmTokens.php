<?php

namespace App\Console\Commands;

use App\Models\FcmToken;
use Illuminate\Console\Command;

class CleanOldFcmTokens extends Command
{
    protected $signature   = 'fcm:clean';
    protected $description = 'Hapus token FCM yang sudah tidak aktif lebih dari 30 hari';

    public function handle()
    {
        $deleted = FcmToken::where('updated_at', '<', now()->subDays(30))->delete();
        $this->info("Token dihapus: {$deleted}");
    }
}
