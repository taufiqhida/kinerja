<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kepala extends Model
{
    protected $table = 'kepala';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'pangkat_golongan',
        'jabatan_id',
        'unit_kerja_id',
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

    public function pegawaiBawahan(): HasMany
    {
        return $this->hasMany(Pegawai::class);
    }

    public function feedbackHasil(): HasMany
    {
        return $this->hasMany(FeedbackHasil::class);
    }

    public function penilaianHasil(): HasMany
    {
        return $this->hasMany(PenilaianHasil::class);
    }

    public function penilaianPerilaku(): HasMany
    {
        return $this->hasMany(PenilaianPerilaku::class);
    }
}
