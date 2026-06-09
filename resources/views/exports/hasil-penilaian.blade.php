<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Penilaian Kinerja - {{ $pegawai->nama_lengkap }}</title>
    <style>
        @page {
            margin: 2.0cm 0;
        }
        div, p, table, tr, td, th, h1, h2, h3, h4, hr, span {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            color: #2d3748;
            line-height: 1.35;
        }
        .content-wrapper {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .kop-surat {
            margin-bottom: 6px;
        }
        .kop-title-1 {
            font-size: 10px;
            font-weight: normal;
            letter-spacing: 1.5px;
            color: #2d3748;
            text-transform: uppercase;
        }
        .kop-title-2 {
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #1a202c;
            margin-top: 1px;
            text-transform: uppercase;
        }
        .kop-title-3 {
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #1e3a5f;
            margin-top: 2px;
            text-transform: uppercase;
        }
        .kop-address, .kop-contact {
            font-size: 8px;
            color: #4a5568;
            margin-top: 2px;
            font-style: italic;
        }
        .kop-line {
            border: none;
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            height: 3px;
            margin-top: 5px;
            margin-bottom: 12px;
        }
        .doc-title {
            font-size: 11px;
            font-weight: bold;
            color: #1e3a5f;
            margin-top: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-subtitle {
            font-size: 9px;
            color: #4a5568;
            margin-top: 1px;
        }
        .section-title {
            font-size: 9.5px;
            font-weight: bold;
            color: #1e3a5f;
            padding: 4px 8px;
            background-color: #ebf4ff;
            border-left: 3px solid #1e3a5f;
            margin: 14px 0 6px 0;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 3px 5px;
            font-size: 9px;
            vertical-align: top;
        }
        .info-table td.label {
            font-weight: bold;
            color: #4a5568;
            width: 22%;
        }
        .info-table td.value {
            color: #1a202c;
            width: 28%;
        }
        
        /* Summary Box */
        .summary-box {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            border-radius: 6px;
            margin: 12px 0 15px 0;
            width: 100%;
        }
        .summary-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 6px 4px;
            width: 25%;
            vertical-align: middle;
            border-right: 1px solid #cbd5e1;
        }
        .summary-item:last-child {
            border-right: none;
        }
        .summary-item .label {
            font-size: 7.5px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .summary-item .value {
            font-size: 14px;
            font-weight: bold;
            color: #1e3a5f;
        }
        .summary-item .sub {
            font-size: 7.5px;
            color: #64748b;
            margin-top: 1px;
        }
        .summary-item.highlight {
            background-color: #eff6ff;
        }
        .summary-item.highlight .value {
            font-size: 16px;
            color: #1d4ed8;
        }

        /* Data Tables */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        table.data-table tr {
            page-break-inside: avoid;
        }
        table.data-table th {
            background-color: #1e3a5f;
            color: #ffffff;
            font-weight: bold;
            font-size: 8px;
            padding: 5px 6px;
            border: 1px solid #1e3a5f;
            text-align: center;
            text-transform: uppercase;
        }
        table.data-table td {
            padding: 5px 6px;
            font-size: 8px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
            color: #334155;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 1.5px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            white-space: nowrap;
            text-align: center;
        }
        .badge-success { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-info { background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .badge-warning { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .badge-secondary { background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }

        /* Footer & Signatures */
        .footer {
            margin-top: 20px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            page-break-inside: avoid;
        }
        .footer-grid {
            display: table;
            width: 100%;
        }
        .footer-left {
            display: table-cell;
            width: 45%;
            vertical-align: top;
            font-size: 8px;
            color: #64748b;
        }
        .footer-right {
            display: table-cell;
            width: 55%;
            text-align: right;
            vertical-align: top;
            font-size: 9px;
            color: #1a202c;
            padding-right: 5px;
        }
        .footer-right p {
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        {{-- Header --}}
        <div class="header">
            <div class="kop-surat">
                <div class="kop-title-1">Pemerintah Kota Semarang</div>
                <div class="kop-title-2">Dinas Kesehatan Kota Semarang</div>
                <div class="kop-title-3">Puskesmas Bugangan</div>
                <div class="kop-address">Jl. Musi Raya No.22, Bugangan, Kec. Semarang Tim., Kota Semarang, Jawa Tengah 50126</div>
                <div class="kop-contact">Telp: (024) 3546061 | Email: puskbug@gmail.com</div>
            </div>
            <hr class="kop-line">
            <h3 class="doc-title">Laporan Hasil Penilaian Kinerja Pegawai</h3>
            <p class="doc-subtitle">Periode Penilaian: {{ $displayPeriodeNama }}</p>
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
                <td class="value">: {{ $pegawai->pangkat_golongan ?? '-' }}</td>
                <td class="label">Periode</td>
                <td class="value">: {{ $displayPeriodeRange }}</td>
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
                    <div class="value">{{ number_format($nilaiAkhir['nilai_hasil_kerja'] ?? 0, 2) }}</div>
                    <div class="sub">× 60% = {{ number_format($nilaiAkhir['nilai_hasil_kerja_bobot'] ?? 0, 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="label">Nilai Perilaku</div>
                    <div class="value">{{ number_format($nilaiAkhir['nilai_perilaku_kerja'] ?? 0, 2) }}</div>
                    <div class="sub">× 40% = {{ number_format($nilaiAkhir['nilai_perilaku_kerja_bobot'] ?? 0, 2) }}</div>
                </div>
                <div class="summary-item highlight">
                    <div class="label">Nilai Akhir</div>
                    <div class="value">{{ number_format($nilaiAkhir['nilai_akhir'] ?? 0, 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="label">Predikat</div>
                    <div class="value">{{ $nilaiAkhir['predikat'] ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Detail Penilaian Hasil Kerja --}}
        <div class="section-title">Penilaian Hasil Kerja (Bobot 60%)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%">NO</th>
                    <th style="width: 35%; text-align: left;">INDIKATOR KINERJA</th>
                    <th style="width: 12%">SATUAN</th>
                    <th style="width: 10%">TARGET</th>
                    <th style="width: 10%">REALISASI</th>
                    <th style="width: 10%">CAPAIAN</th>
                    <th style="width: 18%">PENILAIAN ATASAN</th>
                </tr>
            </thead>
            <tbody>
                @if($indikators->isEmpty())
                    <tr>
                        <td colspan="7" style="text-align: center; font-style: italic; color: #718096; padding: 10px;">Tidak ada data indikator kinerja.</td>
                    </tr>
                @else
                    @foreach($indikators as $i => $indikator)
                        @php
                            $totalRealisasi = $indikator->realisasiKinerja->sum('jumlah_realisasi');
                            $target = $indikator->target_bulanan;
                            $capaian = $target > 0 ? round(($totalRealisasi / $target) * 100, 1) : 0;
                            $penilaian = $indikator->penilaianHasil;
                            $nilaiLabel = $penilaian ? (\App\Models\PenilaianHasil::NILAI_LABEL[$penilaian->nilai] ?? '-') : 'Belum Dinilai';
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $i + 1 }}</td>
                            <td>{{ $indikator->nama_indikator }}</td>
                            <td style="text-align: center;">{{ $indikator->satuan }}</td>
                            <td style="text-align: center;">{{ $target }}</td>
                            <td style="text-align: center;">{{ $totalRealisasi }}</td>
                            <td style="text-align: center;">{{ $capaian }}%</td>
                            <td style="text-align: center;">
                                <span class="badge {{ $penilaian ? match($penilaian->nilai) {
                                    'di_atas_ekspektasi' => 'badge-success',
                                    'sesuai_ekspektasi' => 'badge-info',
                                    'perlu_perbaikan' => 'badge-danger',
                                    default => 'badge-secondary'
                                } : 'badge-secondary' }}">{{ $nilaiLabel }}</span>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        {{-- Penilaian Perilaku Kerja --}}
        <div class="section-title">Penilaian Perilaku Kerja / BerAKHLAK (Bobot 40%)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%">NO</th>
                    <th style="width: 47%; text-align: left;">ASPEK PERILAKU</th>
                    <th style="width: 30%">PENILAIAN</th>
                    <th style="width: 15%">NILAI ANGKA</th>
                </tr>
            </thead>
            <tbody>
                @if(empty($penilaianPerilaku))
                    <tr>
                        <td colspan="4" style="text-align: center; font-style: italic; color: #718096; padding: 10px;">Tidak ada data penilaian perilaku.</td>
                    </tr>
                @else
                    @foreach($penilaianPerilaku as $pp)
                        <tr>
                            <td style="text-align: center;">{{ $pp['urutan'] }}</td>
                            <td>{{ $pp['nama_perilaku'] }}</td>
                            <td style="text-align: center;">
                                <span class="badge {{ match($pp['nilai']) {
                                    'di_atas_ekspektasi' => 'badge-success',
                                    'sesuai_ekspektasi' => 'badge-info',
                                    'perlu_perbaikan' => 'badge-danger',
                                    default => 'badge-secondary'
                                } }}">{{ $pp['nilai_label'] }}</span>
                            </td>
                            <td style="text-align: center;">{{ number_format($pp['nilai_angka'] ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        {{-- Footer / Tanda Tangan --}}
        <div class="footer">
            <div style="font-style: italic; color: #718096; margin-bottom: 15px; font-size: 7.5px;">
                Dicetak otomatis dari Sistem eKinerja Puskesmas Bugangan &bull; Tanggal Cetak: {{ $tanggalCetak }}
            </div>
            <table style="width: 100%; border: none; font-size: 9px; line-height: 1.5; background-color: transparent;">
                <tr style="background-color: transparent;">
                    <td style="width: 50%; text-align: center; border: none; padding: 0; vertical-align: top; background-color: transparent;">
                        <p style="font-weight: bold;">Pegawai Yang Dinilai,</p>
                        <br><br><br><br>
                        <p style="font-weight: bold; text-decoration: underline; color: #000;">{{ $pegawai->nama_lengkap }}</p>
                        <p style="font-size: 8.5px; color: #4a5568; margin-top: 2px;">NIK. {{ $pegawai->nik }}</p>
                    </td>
                    <td style="width: 50%; text-align: center; border: none; padding: 0; vertical-align: top; background-color: transparent;">
                        <p>Semarang, {{ $tanggalCetak }}</p>
                        <p style="font-weight: bold;">Atasan Penilai,</p>
                        <br><br><br><br>
                        <p style="font-weight: bold; text-decoration: underline; color: #000;">{{ $pegawai->kepala?->nama_lengkap ?? '___________________' }}</p>
                        @if($pegawai->kepala?->nip)
                            <p style="font-size: 8.5px; color: #4a5568; margin-top: 2px;">NIP. {{ $pegawai->kepala->nip }}</p>
                        @else
                            <p style="font-size: 8.5px; color: #4a5568; margin-top: 2px;">NIP. -</p>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
