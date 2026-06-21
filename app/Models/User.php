<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\RoomCheck;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function fcmTokens()
    {
        return $this->hasMany(\App\Models\FcmToken::class);
    }

    public function roomChecks()
    {
        return $this->hasMany(RoomCheck::class);
    }

    // ✅ Helper cek role
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    // ✅ Batasi akses panel berdasarkan role
    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin'   => $this->role === 'admin',
            'upt'     => in_array($this->role, ['admin', 'staff']),
            'staff'   => in_array($this->role, ['admin', 'staff']),
            'sarpras' => in_array($this->role, ['admin', 'sarpras']),
            'guru'    => in_array($this->role, ['admin', 'guru', 'sarpras']),
            default   => false,
        };
    }

    public function procurementRequests()
    {
        return $this->hasMany(ProcurementRequest::class);
    }

    public function goodAllocations()
    {
        return $this->hasMany(GoodAllocation::class);
    }
}
