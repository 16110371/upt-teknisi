<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementRequest extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_note',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function items()
    {
        return $this->hasMany(ProcurementItem::class);
    }

    // ✅ Helper status
    public function isPending(): bool
    {
        return $this->status === 'Diajukan';
    }

    public function isApproved(): bool
    {
        return $this->status === 'Disetujui';
    }
}
