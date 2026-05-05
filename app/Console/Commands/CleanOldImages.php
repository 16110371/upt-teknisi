<?php

namespace App\Console\Commands;

use App\Models\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanOldImages extends Command
{
    protected $signature   = 'images:clean';
    protected $description = 'Hapus foto kerusakan yang sudah lebih dari 1 tahun';

    public function handle()
    {
        $requests = Request::whereNotNull('photo')
            ->where('created_at', '<', now()->subYear())
            ->get();

        $deleted = 0;

        foreach ($requests as $request) {
            // ✅ Hapus file dari storage
            if (Storage::disk('public')->exists($request->photo)) {
                Storage::disk('public')->delete($request->photo);
                $deleted++;
            }

            // ✅ Kosongkan kolom photo di database
            $request->updateQuietly(['photo' => null]);
        }

        $this->info("Selesai! {$deleted} foto dihapus.");
    }
}
