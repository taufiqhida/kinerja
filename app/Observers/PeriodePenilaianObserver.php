<?php

namespace App\Observers;

use App\Models\IndikatorKinerja;
use App\Models\PeriodePenilaian;

class PeriodePenilaianObserver
{
    /**
     * Handle the PeriodePenilaian "created" event.
     * Saat periode baru dibuat (apapun status aktifnya),
     * langsung salin indikator dari periode aktif/terbaru yang ada.
     */
    public function created(PeriodePenilaian $periode): void
    {
        // Cari periode lain yang paling baru (aktif duluan, lalu fallback ke semua)
        $periodeSumber = PeriodePenilaian::where('id', '!=', $periode->id)
            ->orderByRaw('is_active DESC') // utamakan yang aktif
            ->orderBy('tanggal_mulai', 'desc')
            ->first();

        if (! $periodeSumber) {
            return;
        }

        static::salinIndikator($periodeSumber->id, $periode->id);
    }

    /**
     * Handle the PeriodePenilaian "updating" event.
     * Saat periode di-aktifkan (is_active: false → true),
     * salin indikator jika periode ini belum punya indikator.
     * TIDAK menonaktifkan periode lain.
     */
    public function updating(PeriodePenilaian $periode): void
    {
        // Hanya jalankan jika is_active berubah dari false → true
        if (! $periode->isDirty('is_active') || ! $periode->is_active) {
            return;
        }

        // Cek apakah sudah ada indikator di periode ini
        $sudahAda = IndikatorKinerja::where('periode_id', $periode->id)->exists();
        if ($sudahAda) {
            return; // Tidak perlu salin lagi
        }

        // Cari periode sumber (aktif terbaru selain periode ini)
        $periodeSumber = PeriodePenilaian::where('is_active', true)
            ->where('id', '!=', $periode->id)
            ->orderBy('tanggal_mulai', 'desc')
            ->first()
            ?? PeriodePenilaian::where('id', '!=', $periode->id)
                ->orderBy('tanggal_mulai', 'desc')
                ->first();

        if (! $periodeSumber) {
            return;
        }

        static::salinIndikator($periodeSumber->id, $periode->id);
    }

    /**
     * Salin semua indikator kinerja dari satu periode ke periode lain.
     * Hanya salin yang belum ada (tidak duplikat).
     */
    public static function salinIndikator(int $dariPeriodeId, int $kePeriodeId): int
    {
        $indikators = IndikatorKinerja::where('periode_id', $dariPeriodeId)->get();
        $disalin = 0;

        foreach ($indikators as $ind) {
            $sudahAda = IndikatorKinerja::where('periode_id', $kePeriodeId)
                ->where('pegawai_id', $ind->pegawai_id)
                ->where('nama_indikator', $ind->nama_indikator)
                ->exists();

            if (! $sudahAda) {
                IndikatorKinerja::create([
                    'pegawai_id'     => $ind->pegawai_id,
                    'periode_id'     => $kePeriodeId,
                    'nama_indikator' => $ind->nama_indikator,
                    'satuan'         => $ind->satuan,
                    'target_bulanan' => $ind->target_bulanan,
                ]);
                $disalin++;
            }
        }

        return $disalin;
    }
}
