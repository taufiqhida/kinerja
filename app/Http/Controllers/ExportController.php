<?php

namespace App\Http\Controllers;

use App\Models\IndikatorKinerja;
use App\Models\Kepala;
use App\Models\Pegawai;
use App\Models\PenilaianPerilaku;
use App\Models\PerilakuMaster;
use App\Models\PeriodePenilaian;
use App\Models\TtdPenilaian;
use App\Services\PerhitunganNilaiService;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Export hasil penilaian pegawai sebagai PDF dengan QR Code TTD.
     */
    public function exportHasilPenilaian(Request $request, ?int $pegawaiId = null)
    {
        $user = auth()->user();

        // Check permissions and get target Pegawai record
        if ($user->role === 'pegawai') {
            $pegawai = Pegawai::where('user_id', $user->id)
                ->with(['jabatan', 'unitKerja', 'kepala'])
                ->firstOrFail();
        } elseif ($user->role === 'kepala') {
            if (!$pegawaiId) {
                abort(404);
            }
            $kepala = $user->kepala;
            if (!$kepala) {
                abort(403, 'Akses ditolak: Data Kepala tidak ditemukan.');
            }
            $pegawai = Pegawai::where('id', $pegawaiId)
                ->where('kepala_id', $kepala->id)
                ->with(['jabatan', 'unitKerja', 'kepala'])
                ->firstOrFail();
        } elseif ($user->role === 'admin') {
            if (!$pegawaiId) {
                abort(404);
            }
            $pegawai = Pegawai::with(['jabatan', 'unitKerja', 'kepala'])
                ->findOrFail($pegawaiId);
        } else {
            abort(403, 'Akses ditolak.');
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

        $displayPeriodeNama  = $periode->nama_periode;
        $displayPeriodeRange = \Carbon\Carbon::parse($periode->tanggal_mulai)->translatedFormat('d F Y')
            . ' s/d '
            . \Carbon\Carbon::parse($periode->tanggal_selesai)->translatedFormat('d F Y');

        // Hitung nilai akhir
        $service   = new PerhitunganNilaiService();
        $nilaiAkhir = $service->hitungNilaiAkhir($pegawai, $periode);

        // Load indikator kinerja + realisasi
        $indikators = IndikatorKinerja::where('pegawai_id', $pegawai->id)
            ->where('periode_id', $periode->id)
            ->with([
                'realisasiKinerja' => fn($q) => $q->orderByDesc('tanggal_realisasi'),
                'penilaianHasil',
            ])
            ->get();

        // Load penilaian perilaku
        $perilakuMasters  = PerilakuMaster::where('is_active', true)->orderBy('urutan')->get();
        $penilaianPerilaku = [];
        foreach ($perilakuMasters as $pm) {
            $penilaian = PenilaianPerilaku::where('pegawai_id', $pegawai->id)
                ->where('periode_id', $periode->id)
                ->where('perilaku_master_id', $pm->id)
                ->first();

            $penilaianPerilaku[] = [
                'nama_perilaku' => $pm->nama_perilaku,
                'urutan'        => $pm->urutan,
                'nilai'         => $penilaian?->nilai ?? null,
                'nilai_label'   => $penilaian ? (PenilaianPerilaku::NILAI_LABEL[$penilaian->nilai] ?? '-') : '-',
                'nilai_angka'   => $penilaian?->nilai_angka ?? 0,
            ];
        }

        // ── TTD / QR Code ──────────────────────────────────────────────────
        $kepalaModel     = $pegawai->kepala;
        $ttdRecord       = null;
        $qrBase64        = null;

        if ($kepalaModel) {
            $ttdRecord = TtdPenilaian::where('pegawai_id', $pegawai->id)
                ->where('kepala_id', $kepalaModel->id)
                ->where('periode_id', $periode->id)
                ->first();

            if ($ttdRecord) {
                $verifikasiUrl = route('ttd.verify', $ttdRecord->token);

                // Generate QR code sebagai PNG base64 untuk DomPDF
                $options = new QROptions([
                    'outputType'  => QRCode::OUTPUT_IMAGE_PNG,
                    'eccLevel'    => QRCode::ECC_L,
                    'scale'       => 4,
                    'imageBase64' => true,
                ]);

                $qrBase64 = (new QRCode($options))->render($verifikasiUrl);
            }
        }
        // ──────────────────────────────────────────────────────────────────

        $data = [
            'pegawai'             => $pegawai,
            'periode'             => $periode,
            'displayPeriodeNama'  => $displayPeriodeNama,
            'displayPeriodeRange' => $displayPeriodeRange,
            'nilaiAkhir'          => $nilaiAkhir,
            'indikators'          => $indikators,
            'penilaianPerilaku'   => $penilaianPerilaku,
            'tanggalCetak'        => now()->translatedFormat('d F Y'),
            'ttdRecord'           => $ttdRecord,
            'qrBase64'            => $qrBase64,
        ];

        $pdf = Pdf::loadView('exports.hasil-penilaian', $data);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Hasil_Penilaian_'
            . str_replace(' ', '_', $pegawai->nama_lengkap)
            . '_'
            . str_replace(' ', '_', $displayPeriodeNama)
            . '.pdf';

        return $pdf->download($filename);
    }
}
