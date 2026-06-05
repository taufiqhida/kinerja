<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RealisasiKinerja extends Model
{
    protected $table = 'realisasi_kinerja';

    protected $fillable = [
        'indikator_kinerja_id',
        'jumlah_realisasi',
        'keterangan',
        'tanggal_realisasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_realisasi' => 'date',
        ];
    }

    public function indikatorKinerja(): BelongsTo
    {
        return $this->belongsTo(IndikatorKinerja::class);
    }
}
