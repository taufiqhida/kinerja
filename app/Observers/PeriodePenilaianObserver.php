<?php

namespace App\Observers;

use App\Models\IndikatorKinerja;
use App\Models\PeriodePenilaian;

class PeriodePenilaianObserver
{
    /**
     * Handle the PeriodePenilaian "updating" event.
     * Saat periode di-aktifkan, nonaktifkan periode lain dan salin indikator.
     */
    public function updating(PeriodePenilaian $periode): void
    {
        // Hanya jalankan jika is_active berubah dari false → true
        if (! $periode->isDirty('is_active') || ! $periode->is_active) {
            return;
        }

        // Cari periode yang sebelumnya aktif (sebelum periode ini diaktifkan)
        $periodeLama = PeriodePenilaian::where('is_active', true)
            ->where('id', '!=', $periode->id)
            ->orderBy('tanggal_mulai', 'desc')
            ->first();

        if (! $periodeLama) {
            return;
        }

        // Salin semua indikator dari periode lama ke periode baru
        static::salinIndikator($periodeLama->id, $periode->id);
    }

    /**
     * Handle the PeriodePenilaian "updated" event.
     * Nonaktifkan semua periode lain setelah periode ini diaktifkan.
     */
    public function updated(PeriodePenilaian $periode): void
    {
        if ($periode->wasChanged('is_active') && $periode->is_active) {
            // Pastikan hanya satu periode aktif
            PeriodePenilaian::where('id', '!=', $periode->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }
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
