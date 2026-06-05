<?php

namespace Database\Seeders;

use App\Models\PerilakuIndikator;
use App\Models\PerilakuMaster;
use Illuminate\Database\Seeder;

class PerilakuSeeder extends Seeder
{
    public function run(): void
    {
        $perilaku = [
            [
                'nama_perilaku' => 'Berorientasi Pelayanan',
                'urutan' => 1,
                'indikator' => [
                    'Memahami dan memenuhi kebutuhan masyarakat',
                    'Ramah, cekatan, solutif dan dapat diandalkan',
                    'Melakukan perbaikan tiada henti',
                ],
            ],
            [
                'nama_perilaku' => 'Akuntabel',
                'urutan' => 2,
                'indikator' => [
                    'Melaksanakan tugas dengan jujur dan bertanggung jawab',
                    'Menggunakan BMN secara efektif dan efisien',
                    'Tidak menyalahgunakan jabatan',
                ],
            ],
            [
                'nama_perilaku' => 'Kompeten',
                'urutan' => 3,
                'indikator' => [
                    'Meningkatkan kompetensi diri',
                    'Membantu orang lain belajar',
                    'Melaksanakan tugas dengan kualitas terbaik',
                ],
            ],
            [
                'nama_perilaku' => 'Harmonis',
                'urutan' => 4,
                'indikator' => [
                    'Menghargai setiap orang',
                    'Suka menolong orang lain',
                    'Membangun lingkungan kerja yang kondusif',
                ],
            ],
            [
                'nama_perilaku' => 'Loyal',
                'urutan' => 5,
                'indikator' => [
                    'Memegang teguh Pancasila dan UUD 1945',
                    'Menjaga nama baik instansi',
                    'Menjaga rahasia jabatan',
                ],
            ],
            [
                'nama_perilaku' => 'Adaptif',
                'urutan' => 6,
                'indikator' => [
                    'Cepat menyesuaikan diri terhadap perubahan',
                    'Terus berinovasi',
                    'Bertindak proaktif',
                ],
            ],
            [
                'nama_perilaku' => 'Kolaboratif',
                'urutan' => 7,
                'indikator' => [
                    'Memberi kesempatan kepada pihak lain untuk berkontribusi',
                    'Terbuka bekerja sama',
                    'Memanfaatkan sumber daya bersama',
                ],
            ],
        ];

        foreach ($perilaku as $p) {
            $master = PerilakuMaster::create([
                'nama_perilaku' => $p['nama_perilaku'],
                'urutan' => $p['urutan'],
                'is_active' => true,
            ]);

            foreach ($p['indikator'] as $i => $deskripsi) {
                PerilakuIndikator::create([
                    'perilaku_master_id' => $master->id,
                    'deskripsi_indikator' => $deskripsi,
                    'urutan' => $i + 1,
                ]);
            }
        }
    }
}
