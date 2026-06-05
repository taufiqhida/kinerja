<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianHasil extends Model
{
    protected $table = 'penilaian_hasil';

    protected $fillable = [
        'indikator_kinerja_id',
        'kepala_id',
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

    /**
     * Map nilai to emoji display.
     */
    public const NILAI_EMOJI = [
        'perlu_perbaikan' => '👎',
        'sesuai_ekspektasi' => '👍',
        'di_atas_ekspektasi' => '👍👍',
    ];

    /**
     * Map nilai to label display.
     */
    public const NILAI_LABEL = [
        'perlu_perbaikan' => 'Di Bawah Ekspektasi',
        'sesuai_ekspektasi' => 'Sesuai Ekspektasi',
        'di_atas_ekspektasi' => 'Di Atas Ekspektasi',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            $model->nilai_angka = self::NILAI_MAP[$model->nilai] ?? 0;
        });
    }

    public function indikatorKinerja(): BelongsTo
    {
        return $this->belongsTo(IndikatorKinerja::class);
    }

    public function kepala(): BelongsTo
    {
        return $this->belongsTo(Kepala::class);
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
