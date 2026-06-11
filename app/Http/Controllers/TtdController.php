<?php

namespace App\Http\Controllers;

use App\Models\TtdPenilaian;
use Illuminate\Http\Request;

class TtdController extends Controller
{
    /**
     * Halaman verifikasi QR code tanda tangan digital.
     * URL ini yang tercetak di QR code pada PDF.
     */
    public function verify(string $token)
    {
        $ttd = TtdPenilaian::where('token', $token)
            ->with(['pegawai.jabatan', 'pegawai.unitKerja', 'kepala', 'periode'])
            ->first();

        if (! $ttd) {
            return view('ttd.invalid');
        }

        return view('ttd.verify', compact('ttd'));
    }

    /**
     * Pegawai mengkonfirmasi tanda tangan (setelah scan QR dan login).
     */
    public function konfirmasi(string $token, Request $request)
    {
        $ttd = TtdPenilaian::where('token', $token)->first();

        if (! $ttd) {
            abort(404, 'Token tidak valid.');
        }

        $user = auth()->user();

        // Pastikan yang konfirmasi adalah pegawai yang bersangkutan
        if ($user?->role !== 'pegawai' || $user->pegawai?->id !== $ttd->pegawai_id) {
            return redirect("/verifikasi-ttd/{$token}")
                ->with('error', 'Anda tidak memiliki akses untuk menandatangani dokumen ini.');
        }

        $ttd->update([
            'ttd_pegawai'       => true,
            'pegawai_signed_at' => now(),
        ]);

        return redirect("/verifikasi-ttd/{$token}")
            ->with('success', 'Tanda tangan berhasil dikonfirmasi!');
    }
}
