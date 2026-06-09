<x-filament-panels::page>
    {{-- Alerts --}}
    @if($jumlahIndikatorBelumAda)
        <div class="rounded-xl p-4" style="background-color:#fefce8;display:flex;align-items:center;gap:12px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ca8a04" style="width:24px;height:24px;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
            <p style="font-size:0.875rem;font-weight:500;color:#854d0e;">
                Anda belum memiliki indikator kinerja untuk periode ini. Silakan tambahkan melalui menu <strong>Indikator Kinerja</strong>.
            </p>
        </div>
    @endif

    @if($adaRealisasiKosong)
        <div class="rounded-xl p-4 mb-4" style="background-color:#eff6ff;display:flex;align-items:center;gap:12px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#3b82f6" style="width:24px;height:24px;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
            </svg>
            <p style="font-size:0.875rem;font-weight:500;color:#1e40af;">
                Beberapa indikator kinerja Anda belum memiliki realisasi. Segera update melalui menu <strong>Realisasi Kinerja</strong>.
            </p>
        </div>
    @endif

    @if($periodeAktif)
        {{-- Period Selector Dropdown --}}
        <div class="rounded-2xl p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
             style="background: linear-gradient(135deg, #f8fafd, #ffffff); border: 1px solid #cbd5e1;">
            <div class="flex items-center gap-3">
                <div style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border-radius: 12px; padding: 10px; display: inline-flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white text-base">Pilih Periode Penilaian</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tampilkan kalkulasi capaian kinerja pada periode yang dipilih.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <label for="period-select" class="text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">Periode:</label>
                <div class="relative" style="display: inline-block;">
                    <select id="period-select" wire:model.live="selectedPeriodeId" 
                            style="min-width: 200px; padding: 10px 36px 10px 16px; font-size: 0.875rem; font-weight: 600; border-radius: 12px; border: 1px solid #cbd5e1; background-color: #ffffff; color: #1e293b; appearance: none; -webkit-appearance: none; cursor: pointer; transition: border-color 0.2s, box-shadow 0.2s; line-height: 1.25;"
                            class="focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none">
                        @foreach($periodeOptions as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                    <div style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #64748b; display: flex; align-items: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:14px;height:14px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
        {{-- Jumlah Indikator --}}
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" style="padding:20px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:44px;height:44px;background:#eef2ff;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4f46e5" style="width:22px;height:22px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                    </svg>
                </div>
                <div>
                    <p style="font-size:0.8rem;color:#6b7280;font-weight:500;">Indikator Kinerja</p>
                    <p style="font-size:1.75rem;font-weight:800;color:#111827;">{{ $jumlahIndikator }}</p>
                </div>
            </div>
        </div>

        {{-- Rata-rata Capaian --}}
        @php
            $capaianColor = $rataCapaian >= 100 ? '#16a34a' : ($rataCapaian >= 50 ? '#ca8a04' : '#dc2626');
            $capaianBg = $rataCapaian >= 100 ? '#f0fdf4' : ($rataCapaian >= 50 ? '#fefce8' : '#fef2f2');
        @endphp
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" style="padding:20px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:44px;height:44px;background:{{ $capaianBg }};border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="{{ $capaianColor }}" style="width:22px;height:22px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>
                </div>
                <div>
                    <p style="font-size:0.8rem;color:#6b7280;font-weight:500;">Rata-rata Capaian</p>
                    <p style="font-size:1.75rem;font-weight:800;color:{{ $capaianColor }};">{{ $rataCapaian }}%</p>
                </div>
            </div>
        </div>

        {{-- Atasan Penilai --}}
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" style="padding:20px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:44px;height:44px;background:#ecfdf5;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#059669" style="width:22px;height:22px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <div>
                    <p style="font-size:0.8rem;color:#6b7280;font-weight:500;">Atasan Penilai</p>
                    <p style="font-size:1rem;font-weight:700;color:#111827;">{{ $pegawai?->kepala?->nama_lengkap ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Profil --}}
    @if($pegawai)
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" style="padding:24px;">
            <h3 style="font-size:1rem;font-weight:700;color:#111827;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4f46e5" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                </svg>
                Profil Saya
            </h3>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
                <div style="padding:12px;background:#f9fafb;border-radius:8px;">
                    <p style="font-size:0.75rem;color:#6b7280;margin-bottom:4px;">Nama Lengkap</p>
                    <p style="font-weight:600;color:#111827;">{{ $pegawai->nama_lengkap }}</p>
                </div>
                <div style="padding:12px;background:#f9fafb;border-radius:8px;">
                    <p style="font-size:0.75rem;color:#6b7280;margin-bottom:4px;">NIK</p>
                    <p style="font-weight:600;color:#111827;">{{ $pegawai->nik }}</p>
                </div>
                <div style="padding:12px;background:#f9fafb;border-radius:8px;">
                    <p style="font-size:0.75rem;color:#6b7280;margin-bottom:4px;">Pangkat/Golongan</p>
                    <p style="font-weight:600;color:#111827;">{{ $pegawai->pangkat_golongan }}</p>
                </div>
                <div style="padding:12px;background:#f9fafb;border-radius:8px;">
                    <p style="font-size:0.75rem;color:#6b7280;margin-bottom:4px;">Jabatan</p>
                    <p style="font-weight:600;color:#111827;">{{ $pegawai->jabatan?->nama_jabatan }}</p>
                </div>
                <div style="padding:12px;background:#f9fafb;border-radius:8px;">
                    <p style="font-size:0.75rem;color:#6b7280;margin-bottom:4px;">Unit Kerja</p>
                    <p style="font-weight:600;color:#111827;">{{ $pegawai->unitKerja?->nama_unit }}</p>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
