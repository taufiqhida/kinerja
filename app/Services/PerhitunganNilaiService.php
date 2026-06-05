<?php

namespace App\Services;

use App\Models\IndikatorKinerja;
use App\Models\Pegawai;
use App\Models\PenilaianHasil;
use App\Models\PenilaianPerilaku;
use App\Models\PeriodePenilaian;

class PerhitunganNilaiService
{
    /**
     * Bobot penilaian sesuai PRD.
     */
    public const BOBOT_HASIL_KERJA = 0.60;
    public const BOBOT_PERILAKU_KERJA = 0.40;

    /**
     * Predikat berdasarkan nilai akhir.
     */
    public const PREDIKAT = [
        ['min' => 91, 'max' => 100, 'label' => 'Sangat Baik', 'color' => 'success'],
        ['min' => 76, 'max' => 90,  'label' => 'Baik', 'color' => 'info'],
        ['min' => 61, 'max' => 75,  'label' => 'Cukup', 'color' => 'warning'],
        ['min' => 51, 'max' => 60,  'label' => 'Kurang', 'color' => 'danger'],
        ['min' => 0,  'max' => 50,  'label' => 'Sangat Kurang', 'color' => 'danger'],
    ];

    /**
     * Hitung rata-rata nilai hasil kerja pegawai pada periode tertentu.
     */
    public function hitungNilaiHasilKerja(Pegawai $pegawai, PeriodePenilaian $periode): float
    {
        $indikators = IndikatorKinerja::where('pegawai_id', $pegawai->id)
            ->where('periode_id', $periode->id)
            ->pluck('id');

        if ($indikators->isEmpty()) {
            return 0;
        }

        $totalNilai = PenilaianHasil::whereIn('indikator_kinerja_id', $indikators)
            ->sum('nilai_angka');

        $jumlahPenilaian = PenilaianHasil::whereIn('indikator_kinerja_id', $indikators)
            ->count();

        if ($jumlahPenilaian === 0) {
            return 0;
        }

        return round($totalNilai / $jumlahPenilaian, 2);
    }

    /**
     * Hitung rata-rata nilai perilaku kerja pegawai pada periode tertentu.
     */
    public function hitungNilaiPerilakuKerja(Pegawai $pegawai, PeriodePenilaian $periode): float
    {
        $penilaian = PenilaianPerilaku::where('pegawai_id', $pegawai->id)
            ->where('periode_id', $periode->id)
            ->get();

        if ($penilaian->isEmpty()) {
            return 0;
        }

        return round($penilaian->avg('nilai_angka'), 2);
    }

    /**
     * Hitung nilai akhir (60% hasil kerja + 40% perilaku kerja).
     */
    public function hitungNilaiAkhir(Pegawai $pegawai, PeriodePenilaian $periode): array
    {
        $nilaiHasil = $this->hitungNilaiHasilKerja($pegawai, $periode);
        $nilaiPerilaku = $this->hitungNilaiPerilakuKerja($pegawai, $periode);

        $nilaiHasilBobot = $nilaiHasil * self::BOBOT_HASIL_KERJA;
        $nilaiPerilakuBobot = $nilaiPerilaku * self::BOBOT_PERILAKU_KERJA;
        $nilaiAkhir = round($nilaiHasilBobot + $nilaiPerilakuBobot, 2);

        return [
            'nilai_hasil_kerja' => $nilaiHasil,
            'nilai_hasil_kerja_bobot' => round($nilaiHasilBobot, 2),
            'nilai_perilaku_kerja' => $nilaiPerilaku,
            'nilai_perilaku_kerja_bobot' => round($nilaiPerilakuBobot, 2),
            'nilai_akhir' => $nilaiAkhir,
            'predikat' => $this->getPredikat($nilaiAkhir),
            'predikat_color' => $this->getPredikatColor($nilaiAkhir),
        ];
    }

    /**
     * Get predikat label from score.
     */
    public function getPredikat(float $nilai): string
    {
        foreach (self::PREDIKAT as $p) {
            if ($nilai >= $p['min'] && $nilai <= $p['max']) {
                return $p['label'];
            }
        }

        return 'Sangat Kurang';
    }

    /**
     * Get predikat color for UI display.
     */
    public function getPredikatColor(float $nilai): string
    {
        foreach (self::PREDIKAT as $p) {
            if ($nilai >= $p['min'] && $nilai <= $p['max']) {
                return $p['color'];
            }
        }

        return 'danger';
    }

    /**
     * Get ranking of all pegawai for a given period.
     */
    public function getRanking(PeriodePenilaian $periode, ?int $kepalaId = null): array
    {
        $query = Pegawai::query();

        if ($kepalaId) {
            $query->where('kepala_id', $kepalaId);
        }

        $pegawaiList = $query->get();
        $ranking = [];

        foreach ($pegawaiList as $pegawai) {
            $result = $this->hitungNilaiAkhir($pegawai, $periode);
            $ranking[] = array_merge($result, [
                'pegawai_id' => $pegawai->id,
                'nama_lengkap' => $pegawai->nama_lengkap,
                'nik' => $pegawai->nik,
                'unit_kerja' => $pegawai->unitKerja?->nama_unit,
                'jabatan' => $pegawai->jabatan?->nama_jabatan,
            ]);
        }

        // Sort by nilai_akhir descending
        usort($ranking, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);

        // Add rank position
        foreach ($ranking as $i => &$item) {
            $item['ranking'] = $i + 1;
        }

        return $ranking;
    }
}
