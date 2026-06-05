<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'user_id',
        'nik',
        'nama_lengkap',
        'pangkat_golongan',
        'jabatan_id',
        'unit_kerja_id',
        'kepala_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function kepala(): BelongsTo
    {
        return $this->belongsTo(Kepala::class);
    }

    public function indikatorKinerja(): HasMany
    {
        return $this->hasMany(IndikatorKinerja::class);
    }

    public function penilaianPerilaku(): HasMany
    {
        return $this->hasMany(PenilaianPerilaku::class);
    }

    /**
     * Get indikator kinerja for a specific period.
     */
    public function indikatorByPeriode(int $periodeId): HasMany
    {
        return $this->indikatorKinerja()->where('periode_id', $periodeId);
    }
}
