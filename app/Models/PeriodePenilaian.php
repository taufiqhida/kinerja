<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodePenilaian extends Model
{
    protected $table = 'periode_penilaian';

    protected $fillable = [
        'nama_periode',
        'tahun',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function indikatorKinerja(): HasMany
    {
        return $this->hasMany(IndikatorKinerja::class, 'periode_id');
    }

    public function penilaianPerilaku(): HasMany
    {
        return $this->hasMany(PenilaianPerilaku::class, 'periode_id');
    }

    /**
     * Get the currently active period.
     */
    public static function getActive(): ?self
    {
        return static::where('is_active', true)->first();
    }
}
