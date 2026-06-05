<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Determine if the user can access the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->is_active) {
            return false;
        }

        // Admin panel digunakan sebagai login terpusat - semua role boleh akses
        // Setelah login, user akan di-redirect ke panel sesuai role-nya
        return match ($panel->getId()) {
            'admin' => true,
            'kepalas' => $this->role === 'kepala',
            'pegawais' => $this->role === 'pegawai',
            default => false,
        };
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPegawai(): bool
    {
        return $this->role === 'pegawai';
    }

    public function isKepala(): bool
    {
        return $this->role === 'kepala';
    }

    public function pegawai(): HasOne
    {
        return $this->hasOne(Pegawai::class);
    }

    public function kepala(): HasOne
    {
        return $this->hasOne(Kepala::class);
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function unreadNotifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class)->where('is_read', false);
    }
}
