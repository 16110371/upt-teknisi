<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Procurement extends Model
{
    protected $fillable = [
        'item_name',
        'purchase_url',
        'quantity',
        'estimated_price',
        'description',
        'status',
        'requested_at',
        'requested_by',
        'position',
        'location_id',
        'signed_document_photo',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    protected static function booted()
    {
        static::deleting(function ($procurement) {
            // Cek apakah ada file yang tersimpan di kolom tersebut
            if ($procurement->signed_document_photo) {
                // Hapus file dari disk 'local'
                Storage::disk('local')->delete($procurement->signed_document_photo);
            }
        });
    }
}
