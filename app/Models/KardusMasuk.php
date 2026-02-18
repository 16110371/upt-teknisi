<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class KardusMasuk extends Model
{
    protected $fillable = [
        'tanggal_masuk',
        'nama_kardus',
        'foto',
        'keterangan',
    ];


    protected static function booted()
    {
        // Saat record dihapus → hapus file foto
        static::deleting(function ($model) {
            if ($model->foto && Storage::disk('public')->exists($model->foto)) {
                Storage::disk('public')->delete($model->foto);
            }
        });

        // Saat record diupdate → jika foto diganti → hapus foto lama
        static::updating(function ($model) {
            if ($model->isDirty('foto')) {
                $oldFoto = $model->getOriginal('foto');

                if ($oldFoto && Storage::disk('public')->exists($oldFoto)) {
                    Storage::disk('public')->delete($oldFoto);
                }
            }
        });
    }
}
