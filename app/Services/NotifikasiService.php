<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\User;

class NotifikasiService
{
    /**
     * Create a notification for a user.
     */
    public function kirim(User $user, string $judul, string $pesan, string $tipe = 'info', ?string $link = null): Notifikasi
    {
        return Notifikasi::create([
            'user_id' => $user->id,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'link' => $link,
        ]);
    }

    /**
     * Notify employee about missing targets.
     */
    public function notifBelumIsiTarget(User $user): Notifikasi
    {
        return $this->kirim(
            $user,
            'Target Belum Diisi',
            'Anda belum mengisi target kinerja untuk periode aktif. Segera lengkapi target kinerja Anda.',
            'warning',
            '/pegawai/indikator-kinerja'
        );
    }

    /**
     * Notify employee about missing realization data.
     */
    public function notifBelumIsiRealisasi(User $user): Notifikasi
    {
        return $this->kirim(
            $user,
            'Realisasi Belum Diisi',
            'Anda memiliki indikator kinerja yang belum diisi realisasinya. Segera update realisasi Anda.',
            'warning',
            '/pegawai/realisasi-kinerja'
        );
    }

    /**
     * Notify supervisor about pending assessments.
     */
    public function notifBelumMenilai(User $user, string $namaPegawai): Notifikasi
    {
        return $this->kirim(
            $user,
            'Penilaian Belum Selesai',
            "Anda belum menyelesaikan penilaian untuk pegawai: {$namaPegawai}. Segera lakukan penilaian.",
            'warning',
            '/kepala/daftar-pegawai'
        );
    }

    /**
     * Notify employee that assessment is complete.
     */
    public function notifPenilaianSelesai(User $user): Notifikasi
    {
        return $this->kirim(
            $user,
            'Penilaian Selesai',
            'Penilaian kinerja Anda telah selesai dilakukan oleh atasan. Silakan cek hasil penilaian Anda.',
            'success',
            '/pegawai/penilaian'
        );
    }

    /**
     * Notify all users about period opening.
     */
    public function notifPeriodeDibuka(User $user, string $namaPeriode): Notifikasi
    {
        return $this->kirim(
            $user,
            'Periode Penilaian Dibuka',
            "Periode penilaian \"{$namaPeriode}\" telah dibuka. Silakan mulai mengisi indikator dan target kinerja.",
            'info'
        );
    }

    /**
     * Notify all users about period closing.
     */
    public function notifPeriodeDitutup(User $user, string $namaPeriode): Notifikasi
    {
        return $this->kirim(
            $user,
            'Periode Penilaian Ditutup',
            "Periode penilaian \"{$namaPeriode}\" telah ditutup.",
            'info'
        );
    }
}
