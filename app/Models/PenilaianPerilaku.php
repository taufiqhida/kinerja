<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianPerilaku extends Model
{
    protected $table = 'penilaian_perilaku';

    protected $fillable = [
        'pegawai_id',
        'kepala_id',
        'periode_id',
        'perilaku_master_id',
        'ekspektasi_pimpinan',
        'feedback',
        'nilai',
        'nilai_angka',
    ];

    /**
     * Map nilai enum to numeric score.
     */
    public const NILAI_MAP = [
        'perlu_perbaikan' => 70,
        'sesuai_ekspektasi' => 85,
        'di_atas_ekspektasi' => 100,
    ];

    public const NILAI_EMOJI = [
        'perlu_perbaikan' => '👎',
        'sesuai_ekspektasi' => '👍',
        'di_atas_ekspektasi' => '👍👍',
    ];

    public const NILAI_LABEL = [
        'perlu_perbaikan' => 'Perlu Perbaikan',
        'sesuai_ekspektasi' => 'Sesuai Ekspektasi',
        'di_atas_ekspektasi' => 'Di Atas Ekspektasi',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            $model->nilai_angka = self::NILAI_MAP[$model->nilai] ?? 0;
        });
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function kepala(): BelongsTo
    {
        return $this->belongsTo(Kepala::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodePenilaian::class, 'periode_id');
    }

    public function perilakuMaster(): BelongsTo
    {
        return $this->belongsTo(PerilakuMaster::class);
    }

    public function getEmojiAttribute(): string
    {
        return self::NILAI_EMOJI[$this->nilai] ?? '';
    }

    public function getLabelAttribute(): string
    {
        return self::NILAI_LABEL[$this->nilai] ?? '';
    }
}
