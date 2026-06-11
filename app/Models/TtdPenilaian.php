<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TtdPenilaian extends Model
{
    protected $table = 'ttd_penilaian';

    protected $fillable = [
        'pegawai_id',
        'kepala_id',
        'periode_id',
        'token',
        'ttd_kepala',
        'ttd_pegawai',
        'kepala_signed_at',
        'pegawai_signed_at',
    ];

    protected $casts = [
        'ttd_kepala'       => 'boolean',
        'ttd_pegawai'      => 'boolean',
        'kepala_signed_at' => 'datetime',
        'pegawai_signed_at' => 'datetime',
    ];

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
        return $this->belongsTo(PeriodePenilaian::class);
    }

    /**
     * Generate atau ambil record TTD untuk kombinasi pegawai-kepala-periode.
     * Dipanggil saat kepala menyimpan penilaian.
     */
    public static function generateOrRefresh(int $pegawaiId, int $kepalaId, int $periodeId): static
    {
        $ttd = static::firstOrNew([
            'pegawai_id' => $pegawaiId,
            'kepala_id'  => $kepalaId,
            'periode_id' => $periodeId,
        ]);

        // Reset atau generate token baru
        $ttd->token            = Str::random(40);
        $ttd->ttd_kepala       = true;
        $ttd->kepala_signed_at = now();
        // Reset TTD pegawai karena penilaian baru disimpan
        $ttd->ttd_pegawai      = false;
        $ttd->pegawai_signed_at = null;
        $ttd->save();

        return $ttd;
    }

    /**
     * URL untuk halaman verifikasi QR code.
     */
    public function getVerifikasiUrlAttribute(): string
    {
        return url("/verifikasi-ttd/{$this->token}");
    }

    /**
     * Status label tanda tangan.
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->ttd_kepala && $this->ttd_pegawai) {
            return 'Ditandatangani Kedua Pihak';
        }
        if ($this->ttd_kepala) {
            return 'Ditandatangani Atasan — Menunggu Pegawai';
        }
        return 'Belum Ditandatangani';
    }
}
