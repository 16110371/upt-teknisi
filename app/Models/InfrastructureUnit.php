<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfrastructureUnit extends Model
{
    protected $fillable = [
        'infrastructure_id',
        'code',
        'status',
        'note',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function infrastructure()
    {
        return $this->belongsTo(Infrastructure::class);
    }

    public function logs()
    {
        return $this->hasMany(UnitLog::class, 'unit_id');
    }

    public function requestUnits()
    {
        return $this->hasMany(RequestUnit::class, 'unit_id');
    }

    // Helper generate kode otomatis
    public static function generateCode(Infrastructure $infra, int $number): string
    {
        // Ambil singkatan kategori
        $category = strtoupper(substr($infra->category->name, 0, 2));

        // Ambil singkatan lokasi (hapus spasi)
        $location = strtoupper(str_replace(' ', '', $infra->location->name));

        // Format: PC-TJKT4-001
        return "{$category}-{$location}-" . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    // ✅ Helper status label
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'good'             => '✅ Baik',
            'broken'           => '🔧 Rusak',
            'permanent_broken' => '❌ Rusak Permanen',
            default            => '-',
        };
    }
}
