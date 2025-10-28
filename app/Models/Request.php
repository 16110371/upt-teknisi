<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_date',
        'requester_name',
        'requester_contact',
        'category_id',
        'location_id',
        'description',
        'status',
        'technician_id',
        'handled_at',
        'photo',
    ];

    /**
     * Relasi ke kategori (Category)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke lokasi (Location)
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Relasi ke teknisi (Technician)
     */
    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    /**
     * Atur tanggal otomatis jika dibuat dari luar dashboard
     */
    protected static function booted(): void
    {
        static::creating(function ($request) {
            if (empty($request->request_date)) {
                $request->request_date = now()->toDateString();
            }
        });
    }
}
