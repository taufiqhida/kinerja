<?php

namespace App\Http\Controllers;

use App\Models\IndikatorKinerja;
use App\Models\Pegawai;
use App\Models\PenilaianPerilaku;
use App\Models\PerilakuMaster;
use App\Models\PeriodePenilaian;
use App\Services\PerhitunganNilaiService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Export hasil penilaian pegawai sebagai PDF.
     */
    public function exportHasilPenilaian(Request $request, ?int $pegawaiId = null)
    {
        $user = auth()->user();

        // Jika pegawai, hanya bisa export milik sendiri
        if ($user->role === 'pegawai') {
            $pegawai = Pegawai::where('user_id', $user->id)->with(['jabatan', 'unitKerja', 'kepala'])->firstOrFail();
        } elseif ($pegawaiId) {
            $pegawai = Pegawai::with(['jabatan', 'unitKerja', 'kepala'])->findOrFail($pegawaiId);
        } else {
            abort(404);
        }

        $periodeId = $request->query('periode_id');
        if ($periodeId) {
            $periode = PeriodePenilaian::find($periodeId);
        } else {
            $periode = PeriodePenilaian::getActive();
        }

        if (! $periode) {
            return back()->with('error', 'Tidak ada periode penilaian.');
        }

        $displayPeriodeNama = $periode->nama_periode;
        $displayPeriodeRange = \Carbon\Carbon::parse($periode->tanggal_mulai)->translatedFormat('d F Y') . ' s/d ' . \Carbon\Carbon::parse($periode->tanggal_selesai)->translatedFormat('d F Y');

        // Hitung nilai akhir
        $service = new PerhitunganNilaiService();
        $nilaiAkhir = $service->hitungNilaiAkhir($pegawai, $periode);

        // Load indikator kinerja + realisasi
        $indikators = IndikatorKinerja::where('pegawai_id', $pegawai->id)
            ->where('periode_id', $periode->id)
            ->with([
                'realisasiKinerja' => function ($q) {
                    $q->orderByDesc('tanggal_realisasi');
                },
                'penilaianHasil'
            ])
            ->get();

        // Load penilaian perilaku
        $perilakuMasters = PerilakuMaster::where('is_active', true)->orderBy('urutan')->get();
        $penilaianPerilaku = [];
        foreach ($perilakuMasters as $pm) {
            $penilaian = PenilaianPerilaku::where('pegawai_id', $pegawai->id)
                ->where('periode_id', $periode->id)
                ->where('perilaku_master_id', $pm->id)
                ->first();

            $penilaianPerilaku[] = [
                'nama_perilaku' => $pm->nama_perilaku,
                'urutan' => $pm->urutan,
                'nilai' => $penilaian?->nilai ?? null,
                'nilai_label' => $penilaian ? (PenilaianPerilaku::NILAI_LABEL[$penilaian->nilai] ?? '-') : '-',
                'nilai_angka' => $penilaian?->nilai_angka ?? 0,
            ];
        }

        $data = [
            'pegawai' => $pegawai,
            'periode' => $periode,
            'displayPeriodeNama' => $displayPeriodeNama,
            'displayPeriodeRange' => $displayPeriodeRange,
            'nilaiAkhir' => $nilaiAkhir,
            'indikators' => $indikators,
            'penilaianPerilaku' => $penilaianPerilaku,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('exports.hasil-penilaian', $data);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Hasil_Penilaian_' . str_replace(' ', '_', $pegawai->nama_lengkap) . '_' . str_replace(' ', '_', $displayPeriodeNama) . '.pdf';

        return $pdf->download($filename);
    }
}
