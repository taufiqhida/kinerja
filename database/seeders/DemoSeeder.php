<?php

namespace Database\Seeders;

use App\Models\IndikatorKinerja;
use App\Models\Jabatan;
use App\Models\Kepala;
use App\Models\Pegawai;
use App\Models\PeriodePenilaian;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create unit kerja
        $unitPoli = UnitKerja::create(['nama_unit' => 'Poli Umum', 'kode_unit' => 'PU']);
        $unitIGD = UnitKerja::create(['nama_unit' => 'Instalasi Gawat Darurat', 'kode_unit' => 'IGD']);
        $unitLab = UnitKerja::create(['nama_unit' => 'Laboratorium', 'kode_unit' => 'LAB']);

        // Create jabatan
        $jabDokter = Jabatan::create(['nama_jabatan' => 'Dokter Umum', 'kelas_jabatan' => '9']);
        $jabPerawat = Jabatan::create(['nama_jabatan' => 'Perawat', 'kelas_jabatan' => '7']);
        $jabAnalis = Jabatan::create(['nama_jabatan' => 'Analis Kesehatan', 'kelas_jabatan' => '8']);
        $jabKepala = Jabatan::create(['nama_jabatan' => 'Kepala Unit', 'kelas_jabatan' => '11']);

        // Create periode penilaian
        $periode = PeriodePenilaian::create([
            'nama_periode' => 'Semester 1 - 2026',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => true,
        ]);

        // Create kepala account
        $userKepala1 = User::create([
            'name' => 'Dr. Ahmad Suryadi',
            'email' => 'kepala1@simkin.test',
            'password' => bcrypt('password'),
            'role' => 'kepala',
            'is_active' => true,
        ]);
        $kepala1 = Kepala::create([
            'user_id' => $userKepala1->id,
            'nip' => '198001012010011001',
            'nama_lengkap' => 'Dr. Ahmad Suryadi',
            'pangkat_golongan' => 'IV/a - Pembina',
            'jabatan_id' => $jabKepala->id,
            'unit_kerja_id' => $unitPoli->id,
        ]);

        $userKepala2 = User::create([
            'name' => 'Dr. Siti Rahayu',
            'email' => 'kepala2@simkin.test',
            'password' => bcrypt('password'),
            'role' => 'kepala',
            'is_active' => true,
        ]);
        $kepala2 = Kepala::create([
            'user_id' => $userKepala2->id,
            'nip' => '198205152011012002',
            'nama_lengkap' => 'Dr. Siti Rahayu',
            'pangkat_golongan' => 'III/d - Penata Tk. I',
            'jabatan_id' => $jabKepala->id,
            'unit_kerja_id' => $unitIGD->id,
        ]);

        // Create pegawai accounts
        $pegawaiData = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@simkin.test',
                'nik' => '3201010101010001',
                'pangkat' => 'III/b - Penata Muda Tk. I',
                'jabatan_id' => $jabPerawat->id,
                'unit_kerja_id' => $unitPoli->id,
                'kepala_id' => $kepala1->id,
                'indikator' => [
                    ['nama' => 'Jumlah Pengkajian Pasien', 'satuan' => 'pasien', 'target' => 100],
                    ['nama' => 'Pengelolaan Program TBC', 'satuan' => 'kegiatan', 'target' => 12],
                    ['nama' => 'Penyusunan Laporan Bulanan', 'satuan' => 'laporan', 'target' => 12],
                ],
            ],
            [
                'name' => 'Dewi Anggraini',
                'email' => 'dewi@simkin.test',
                'nik' => '3201010101010002',
                'pangkat' => 'III/a - Penata Muda',
                'jabatan_id' => $jabPerawat->id,
                'unit_kerja_id' => $unitPoli->id,
                'kepala_id' => $kepala1->id,
                'indikator' => [
                    ['nama' => 'Pelayanan Vaksinasi', 'satuan' => 'kegiatan', 'target' => 48],
                    ['nama' => 'Pengelolaan Rekam Medis', 'satuan' => 'rekam', 'target' => 200],
                    ['nama' => 'Penyusunan Laporan Bulanan', 'satuan' => 'laporan', 'target' => 12],
                ],
            ],
            [
                'name' => 'Rizky Pratama',
                'email' => 'rizky@simkin.test',
                'nik' => '3201010101010003',
                'pangkat' => 'III/c - Penata',
                'jabatan_id' => $jabDokter->id,
                'unit_kerja_id' => $unitIGD->id,
                'kepala_id' => $kepala2->id,
                'indikator' => [
                    ['nama' => 'Penanganan Pasien Darurat', 'satuan' => 'pasien', 'target' => 200],
                    ['nama' => 'Pelatihan BLS/ACLS', 'satuan' => 'kegiatan', 'target' => 4],
                    ['nama' => 'Laporan Kasus Bulanan', 'satuan' => 'laporan', 'target' => 12],
                ],
            ],
            [
                'name' => 'Fitriani Putri',
                'email' => 'fitri@simkin.test',
                'nik' => '3201010101010004',
                'pangkat' => 'III/a - Penata Muda',
                'jabatan_id' => $jabAnalis->id,
                'unit_kerja_id' => $unitLab->id,
                'kepala_id' => $kepala2->id,
                'indikator' => [
                    ['nama' => 'Pemeriksaan Laboratorium', 'satuan' => 'sampel', 'target' => 500],
                    ['nama' => 'Kalibrasi Alat', 'satuan' => 'kegiatan', 'target' => 6],
                    ['nama' => 'Laporan Hasil Lab Bulanan', 'satuan' => 'laporan', 'target' => 12],
                ],
            ],
        ];

        foreach ($pegawaiData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'),
                'role' => 'pegawai',
                'is_active' => true,
            ]);

            $pegawai = Pegawai::create([
                'user_id' => $user->id,
                'nik' => $data['nik'],
                'nama_lengkap' => $data['name'],
                'pangkat_golongan' => $data['pangkat'],
                'jabatan_id' => $data['jabatan_id'],
                'unit_kerja_id' => $data['unit_kerja_id'],
                'kepala_id' => $data['kepala_id'],
            ]);

            foreach ($data['indikator'] as $ind) {
                IndikatorKinerja::create([
                    'pegawai_id' => $pegawai->id,
                    'periode_id' => $periode->id,
                    'nama_indikator' => $ind['nama'],
                    'satuan' => $ind['satuan'],
                    'target_tahunan' => $ind['target'],
                ]);
            }
        }
    }
}
