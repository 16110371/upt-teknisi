<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
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
        'completed_at',
        'technician_note',
    ];

    protected $casts = [
        'request_date' => 'date',
        'handled_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            if (empty($request->request_date)) {
                $request->request_date = now()->toDateString();
            }
        });

        static::deleting(function ($request) {
            if ($request->photo && Storage::disk('public')->exists($request->photo)) {
                Storage::disk('public')->delete($request->photo);
            }
        });

        static::updating(function ($request) {

            if (
                $request->isDirty('photo') &&
                filled($request->getOriginal('photo'))
            ) {
                $oldPhoto = $request->getOriginal('photo');

                if (Storage::disk('public')->exists($oldPhoto)) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }

            if ($request->isDirty('status') && $request->status === 'Proses') {
                $request->handled_at = now();
            }
        });
    }
}
