<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dokumen – eKinerja Puskesmas Bugangan</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: #fff;
            border-radius: 20px;
            padding: 36px;
            max-width: 560px;
            width: 100%;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
        }
        .logo-bar {
            text-align: center;
            margin-bottom: 24px;
        }
        .logo-bar .badge-verified {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ecfdf5;
            border: 1.5px solid #6ee7b7;
            border-radius: 100px;
            padding: 8px 20px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #065f46;
        }
        .logo-bar .badge-pending {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fffbeb;
            border: 1.5px solid #fde68a;
            border-radius: 100px;
            padding: 8px 20px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #92400e;
        }
        h1 { font-size: 1.4rem; font-weight: 800; color: #1e293b; margin-bottom: 4px; }
        .subtitle { font-size: 0.82rem; color: #64748b; margin-bottom: 24px; }
        .divider { border: none; border-top: 1px solid #f1f5f9; margin: 20px 0; }
        .info-row {
            display: flex;
            gap: 12px;
            margin-bottom: 10px;
            align-items: flex-start;
        }
        .info-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            width: 130px;
            flex-shrink: 0;
            padding-top: 1px;
        }
        .info-value {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1e293b;
            flex: 1;
        }
        .ttd-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 20px;
        }
        .ttd-box {
            border-radius: 12px;
            padding: 16px;
            text-align: center;
        }
        .ttd-box.signed {
            background: #f0fdf4;
            border: 2px solid #86efac;
        }
        .ttd-box.pending {
            background: #fffbeb;
            border: 2px dashed #fde68a;
        }
        .ttd-box .role {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 6px;
        }
        .ttd-box .name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .ttd-box .status {
            font-size: 0.75rem;
            font-weight: 600;
        }
        .ttd-box.signed .status { color: #16a34a; }
        .ttd-box.pending .status { color: #d97706; }
        .ttd-box .timestamp {
            font-size: 0.68rem;
            color: #94a3b8;
            margin-top: 4px;
        }
        .doc-id {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .doc-id .id-label { font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; }
        .doc-id .id-value { font-size: 0.8rem; font-weight: 700; color: #1e293b; font-family: monospace; }
        .btn-konfirmasi {
            display: block;
            width: 100%;
            margin-top: 20px;
            padding: 14px;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 0.82rem;
            font-weight: 600;
        }
        .alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #15803d; }
        .alert-error { background: #fef2f2; border: 1px solid #fca5a5; color: #dc2626; }
        .footer-note { text-align: center; font-size: 0.7rem; color: #94a3b8; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="card">
        {{-- Logo / Badge --}}
        <div class="logo-bar">
            @if($ttd->ttd_kepala && $ttd->ttd_pegawai)
                <div class="badge-verified">✅ Dokumen Terverifikasi Penuh</div>
            @else
                <div class="badge-pending">⏳ Menunggu Konfirmasi Pegawai</div>
            @endif
        </div>

        <h1>Verifikasi Dokumen</h1>
        <p class="subtitle">Sistem eKinerja – Puskesmas Bugangan, Kota Semarang</p>

        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">⚠ {{ session('error') }}</div>
        @endif

        <hr class="divider">

        {{-- Info Pegawai --}}
        <div class="info-row">
            <span class="info-label">Pegawai</span>
            <span class="info-value">{{ $ttd->pegawai->nama_lengkap }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">NIK</span>
            <span class="info-value">{{ $ttd->pegawai->nik }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jabatan</span>
            <span class="info-value">{{ $ttd->pegawai->jabatan?->nama_jabatan ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Periode Penilaian</span>
            <span class="info-value">{{ $ttd->periode->nama_periode }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Atasan Penilai</span>
            <span class="info-value">{{ $ttd->kepala->nama_lengkap }}</span>
        </div>

        <hr class="divider">

        {{-- Status TTD --}}
        <div style="font-size:0.72rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:10px;">Status Tanda Tangan</div>
        <div class="ttd-grid">
            {{-- Kepala --}}
            <div class="ttd-box {{ $ttd->ttd_kepala ? 'signed' : 'pending' }}">
                <div class="role">Atasan Penilai</div>
                <div class="name">{{ $ttd->kepala->nama_lengkap }}</div>
                @if($ttd->ttd_kepala)
                    <div class="status">✅ Ditandatangani</div>
                    @if($ttd->kepala_signed_at)
                        <div class="timestamp">{{ \Carbon\Carbon::parse($ttd->kepala_signed_at)->translatedFormat('d M Y, H:i') }} WIB</div>
                    @endif
                @else
                    <div class="status">⏳ Belum Ditandatangani</div>
                @endif
            </div>
            {{-- Pegawai --}}
            <div class="ttd-box {{ $ttd->ttd_pegawai ? 'signed' : 'pending' }}">
                <div class="role">Pegawai</div>
                <div class="name">{{ $ttd->pegawai->nama_lengkap }}</div>
                @if($ttd->ttd_pegawai)
                    <div class="status">✅ Dikonfirmasi</div>
                    @if($ttd->pegawai_signed_at)
                        <div class="timestamp">{{ \Carbon\Carbon::parse($ttd->pegawai_signed_at)->translatedFormat('d M Y, H:i') }} WIB</div>
                    @endif
                @else
                    <div class="status">⏳ Menunggu Konfirmasi</div>
                @endif
            </div>
        </div>

        {{-- ID Dokumen --}}
        <div class="doc-id">
            <div>
                <div class="id-label">ID Dokumen</div>
                <div class="id-value">{{ strtoupper(substr($ttd->token, 0, 10)) }}...{{ strtoupper(substr($ttd->token, -6)) }}</div>
            </div>
        </div>

        {{-- Tombol konfirmasi untuk pegawai yang sudah login --}}
        @if(! $ttd->ttd_pegawai && auth()->check() && auth()->user()->role === 'pegawai')
            @php $userPegawai = auth()->user()->pegawai; @endphp
            @if($userPegawai && $userPegawai->id === $ttd->pegawai_id)
                <form method="POST" action="{{ route('ttd.konfirmasi', $ttd->token) }}">
                    @csrf
                    <button type="submit" class="btn-konfirmasi">
                        ✍️ Konfirmasi Tanda Tangan Saya
                    </button>
                </form>
            @endif
        @endif

        <p class="footer-note">
            Dokumen ini diterbitkan oleh Sistem eKinerja Puskesmas Bugangan.<br>
            Verifikasi dilakukan secara otomatis berdasarkan token unik dokumen.
        </p>
    </div>
</body>
</html>
