<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Penilaian Kinerja - {{ $pegawai->nama_lengkap }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; line-height: 1.5; }

        .header { text-align: center; border-bottom: 3px double #1e3a5f; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 16px; font-weight: bold; color: #1e3a5f; letter-spacing: 1px; }
        .header h2 { font-size: 12px; font-weight: normal; color: #555; margin-top: 2px; }
        .header p { font-size: 9px; color: #777; margin-top: 4px; }

        .section-title { font-size: 11px; font-weight: bold; color: #1e3a5f; padding: 6px 10px; background: #eef3f9; border-left: 3px solid #1e3a5f; margin: 16px 0 8px 0; }

        .info-table { width: 100%; margin-bottom: 12px; }
        .info-table td { padding: 3px 8px; font-size: 10px; }
        .info-table .label { color: #777; width: 140px; font-weight: bold; }
        .info-table .value { color: #333; }

        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.data-table th { background: #1e3a5f; color: #fff; font-weight: bold; padding: 6px 8px; font-size: 9px; text-align: left; }
        table.data-table td { padding: 5px 8px; font-size: 9px; border-bottom: 1px solid #e0e0e0; }
        table.data-table tr:nth-child(even) { background: #f8f9fa; }

        .summary-box { border: 2px solid #1e3a5f; border-radius: 6px; padding: 12px; margin: 16px 0; }
        .summary-grid { display: table; width: 100%; }
        .summary-item { display: table-cell; text-align: center; padding: 8px; width: 20%; }
        .summary-item .label { font-size: 8px; color: #777; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-item .value { font-size: 18px; font-weight: bold; color: #1e3a5f; margin-top: 2px; }
        .summary-item .sub { font-size: 8px; color: #999; }
        .summary-item.highlight .value { font-size: 22px; color: #0e7490; }

        .footer { margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; }
        .footer-grid { display: table; width: 100%; }
        .footer-left { display: table-cell; width: 50%; vertical-align: top; }
        .footer-right { display: table-cell; width: 50%; text-align: right; vertical-align: top; }
        .footer p { font-size: 9px; color: #777; }
        .ttd { margin-top: 60px; font-size: 10px; color: #333; }
        .ttd .nama { font-weight: bold; text-decoration: underline; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 8px; font-weight: bold; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>PUSKESMAS BUGANGAN</h1>
        <h2>KOTA SEMARANG</h2>
        <p>Laporan Hasil Penilaian Kinerja Pegawai</p>
    </div>

    {{-- Data Pegawai --}}
    <div class="section-title">Data Pegawai</div>
    <table class="info-table">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="value">: {{ $pegawai->nama_lengkap }}</td>
            <td class="label">Unit Kerja</td>
            <td class="value">: {{ $pegawai->unitKerja?->nama_unit ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">NIK</td>
            <td class="value">: {{ $pegawai->nik }}</td>
            <td class="label">Jabatan</td>
            <td class="value">: {{ $pegawai->jabatan?->nama_jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Pangkat/Golongan</td>
            <td class="value">: {{ $pegawai->pangkat_golongan }}</td>
            <td class="label">Periode</td>
            <td class="value">: {{ $periode->nama_periode }}</td>
        </tr>
        <tr>
            <td class="label">Atasan Penilai</td>
            <td class="value">: {{ $pegawai->kepala?->nama_lengkap ?? '-' }}</td>
            <td class="label">Tanggal Cetak</td>
            <td class="value">: {{ $tanggalCetak }}</td>
        </tr>
    </table>

    {{-- Ringkasan Nilai --}}
    <div class="summary-box">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Nilai Hasil Kerja</div>
                <div class="value">{{ $nilaiAkhir['nilai_hasil_kerja'] }}</div>
                <div class="sub">× 60% = {{ $nilaiAkhir['nilai_hasil_kerja_bobot'] }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Nilai Perilaku</div>
                <div class="value">{{ $nilaiAkhir['nilai_perilaku_kerja'] }}</div>
                <div class="sub">× 40% = {{ $nilaiAkhir['nilai_perilaku_kerja_bobot'] }}</div>
            </div>
            <div class="summary-item highlight">
                <div class="label">Nilai Akhir</div>
                <div class="value">{{ $nilaiAkhir['nilai_akhir'] }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Predikat</div>
                <div class="value">{{ $nilaiAkhir['predikat'] }}</div>
            </div>
        </div>
    </div>

    {{-- Detail Penilaian Hasil Kerja --}}
    <div class="section-title">Penilaian Hasil Kerja (Bobot 60%)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:30%">Indikator Kinerja</th>
                <th style="width:10%">Satuan</th>
                <th style="width:10%">Target</th>
                <th style="width:10%">Realisasi</th>
                <th style="width:10%">Capaian</th>
                <th style="width:15%">Penilaian Atasan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($indikators as $i => $indikator)
                @php
                    $totalRealisasi = $indikator->realisasiKinerja->sum('jumlah_realisasi');
                    $target = $indikator->target_bulanan;
                    $capaian = $target > 0 ? round(($totalRealisasi / $target) * 100, 1) : 0;
                    $penilaian = $indikator->penilaianHasil;
                    $nilaiLabel = $penilaian ? (\App\Models\PenilaianHasil::NILAI_LABEL[$penilaian->nilai] ?? '-') : 'Belum Dinilai';
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $indikator->nama_indikator }}</td>
                    <td>{{ $indikator->satuan }}</td>
                    <td style="text-align:center">{{ $target }}</td>
                    <td style="text-align:center">{{ $totalRealisasi }}</td>
                    <td style="text-align:center">{{ $capaian }}%</td>
                    <td>
                        <span class="badge {{ $penilaian ? match($penilaian->nilai) {
                            'di_atas_ekspektasi' => 'badge-success',
                            'sesuai_ekspektasi' => 'badge-info',
                            'perlu_perbaikan' => 'badge-danger',
                            default => ''
                        } : '' }}">{{ $nilaiLabel }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Penilaian Perilaku Kerja --}}
    <div class="section-title">Penilaian Perilaku Kerja / BerAKHLAK (Bobot 40%)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:45%">Aspek Perilaku</th>
                <th style="width:25%">Penilaian</th>
                <th style="width:15%">Nilai Angka</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penilaianPerilaku as $pp)
                <tr>
                    <td>{{ $pp['urutan'] }}</td>
                    <td>{{ $pp['nama_perilaku'] }}</td>
                    <td>
                        <span class="badge {{ match($pp['nilai']) {
                            'di_atas_ekspektasi' => 'badge-success',
                            'sesuai_ekspektasi' => 'badge-info',
                            'perlu_perbaikan' => 'badge-danger',
                            default => ''
                        } }}">{{ $pp['nilai_label'] }}</span>
                    </td>
                    <td style="text-align:center">{{ $pp['nilai_angka'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Footer / Tanda Tangan --}}
    <div class="footer">
        <div class="footer-grid">
            <div class="footer-left">
                <p>Dicetak dari Sistem eKinerja Puskesmas Bugangan</p>
                <p>Tanggal: {{ $tanggalCetak }}</p>
            </div>
            <div class="footer-right">
                <p>Semarang, {{ $tanggalCetak }}</p>
                <p>Atasan Penilai,</p>
                <div class="ttd">
                    <p class="nama">{{ $pegawai->kepala?->nama_lengkap ?? '___________________' }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
