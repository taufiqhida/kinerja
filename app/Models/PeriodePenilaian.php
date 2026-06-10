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
     * Prioritaskan periode yang tanggalnya mencakup hari ini.
     * Jika tidak ada, ambil periode aktif dengan tanggal mulai terbaru.
     * Beberapa periode boleh aktif bersamaan.
     */
    public static function getActive(): ?self
    {
        $today = now()->toDateString();

        // Cari periode aktif yang mencakup hari ini
        $periodeHariIni = static::where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->orderBy('tanggal_mulai', 'desc')
            ->first();

        if ($periodeHariIni) {
            return $periodeHariIni;
        }

        // Fallback: periode aktif dengan tanggal mulai paling baru
        return static::where('is_active', true)
            ->orderBy('tanggal_mulai', 'desc')
            ->first()
            ?? static::orderBy('tanggal_mulai', 'desc')->first();
    }
}
