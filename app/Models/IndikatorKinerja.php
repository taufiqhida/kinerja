<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IndikatorKinerja extends Model
{
    protected $table = 'indikator_kinerja';

    protected $fillable = [
        'pegawai_id',
        'periode_id',
        'nama_indikator',
        'satuan',
        'target_tahunan',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodePenilaian::class, 'periode_id');
    }

    public function realisasiKinerja(): HasMany
    {
        return $this->hasMany(RealisasiKinerja::class);
    }

    public function buktiDukung(): HasMany
    {
        return $this->hasMany(BuktiDukungLink::class);
    }

    public function feedbackHasil(): HasMany
    {
        return $this->hasMany(FeedbackHasil::class);
    }

    public function penilaianHasil(): HasOne
    {
        return $this->hasOne(PenilaianHasil::class);
    }

    /**
     * Get total realisasi for this indicator.
     */
    public function getTotalRealisasiAttribute(): int
    {
        return $this->realisasiKinerja()->sum('jumlah_realisasi');
    }

    /**
     * Get capaian percentage.
     */
    public function getCapaianPersenAttribute(): float
    {
        if ($this->target_tahunan == 0) {
            return 0;
        }

        return round(($this->total_realisasi / $this->target_tahunan) * 100, 2);
    }
}
