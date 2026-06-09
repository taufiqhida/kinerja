<x-filament-panels::page>

    {{-- ====== HEADER BAR: Export + Title ====== --}}
    <div class="flex items-center justify-between mb-1">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Detail penilaian kinerja pegawai</p>
        </div>
        <a href="{{ route('export.hasil-penilaian', ['pegawaiId' => $record->id, 'month' => $selectedMonth]) }}"
           target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm"
           style="background:linear-gradient(135deg,#059669,#10b981);color:white;text-decoration:none;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Export PDF
        </a>
    </div>

    {{-- ====== INFO PEGAWAI ====== --}}
    <div class="rounded-2xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3"
             style="background:linear-gradient(to right, #f8faff, #fff);">
            <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:10px;padding:8px;flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" style="width:18px;height:18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Informasi Pegawai</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-x-8 gap-y-4 md:grid-cols-3">
                <div>
                    <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Nama Lengkap</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $record->nama_lengkap }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">NIK</p>
                    <p class="font-medium text-gray-700 dark:text-gray-300">{{ $record->nik }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Pangkat/Golongan</p>
                    <p class="font-medium text-gray-700 dark:text-gray-300">{{ $record->pangkat_golongan }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Jabatan</p>
                    <p class="font-medium text-gray-700 dark:text-gray-300">{{ $record->jabatan?->nama_jabatan }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Unit Kerja</p>
                    <p class="font-medium text-gray-700 dark:text-gray-300">{{ $record->unitKerja?->nama_unit }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Periode</p>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                          style="background:#eff6ff;color:#1d4ed8;">
                        <span style="width:6px;height:6px;border-radius:50%;background:#3b82f6;display:inline-block;"></span>
                        {{ $periodeAktif?->nama_periode ?? '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if($periodeAktif)

        {{-- Month Selector Dropdown --}}
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
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tampilkan realisasi kinerja pegawai pada periode yang dipilih.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <label for="month-select" class="text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">Periode:</label>
                <div class="relative" style="display: inline-block;">
                    <select id="month-select" wire:model.live="selectedMonth" 
                            style="min-width: 200px; padding: 10px 36px 10px 16px; font-size: 0.875rem; font-weight: 600; border-radius: 12px; border: 1px solid #cbd5e1; background-color: #ffffff; color: #1e293b; appearance: none; -webkit-appearance: none; cursor: pointer; transition: border-color 0.2s, box-shadow 0.2s; line-height: 1.25;"
                            class="focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:outline-none">
                        @foreach($this->getMonthsInPeriod() as $m)
                            <option value="{{ $m['value'] }}">{{ $m['label'] }}</option>
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

        {{-- ====== RINGKASAN NILAI ====== --}}
        <div class="rounded-2xl overflow-hidden shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10"
             style="background:linear-gradient(135deg,#0f172a,#1e3a8a,#1d4ed8);">
            <div class="p-6">
                <div class="flex items-center gap-2 mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="rgba(255,255,255,0.8)" style="width:18px;height:18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                    </svg>
                    <h3 style="color:rgba(255,255,255,0.9);font-size:0.9rem;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;">Ringkasan Nilai</h3>
                </div>
                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    {{-- Nilai Hasil Kerja --}}
                    <div style="background:rgba(255,255,255,0.1);border-radius:12px;padding:16px;text-align:center;backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.15);">
                        <p style="color:rgba(255,255,255,0.6);font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">Hasil Kerja</p>
                        <p style="color:white;font-size:1.8rem;font-weight:700;line-height:1;">{{ $nilaiAkhir['nilai_hasil_kerja'] ?? 0 }}</p>
                        <p style="color:rgba(255,255,255,0.45);font-size:0.7rem;margin-top:4px;">× 60% = {{ $nilaiAkhir['nilai_hasil_kerja_bobot'] ?? 0 }}</p>
                    </div>
                    {{-- Nilai Perilaku --}}
                    <div style="background:rgba(255,255,255,0.1);border-radius:12px;padding:16px;text-align:center;backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.15);">
                        <p style="color:rgba(255,255,255,0.6);font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">Perilaku</p>
                        <p style="color:white;font-size:1.8rem;font-weight:700;line-height:1;">{{ $nilaiAkhir['nilai_perilaku_kerja'] ?? 0 }}</p>
                        <p style="color:rgba(255,255,255,0.45);font-size:0.7rem;margin-top:4px;">× 40% = {{ $nilaiAkhir['nilai_perilaku_kerja_bobot'] ?? 0 }}</p>
                    </div>
                    {{-- Nilai Akhir --}}
                    <div style="background:rgba(99,179,237,0.2);border-radius:12px;padding:16px;text-align:center;border:1px solid rgba(99,179,237,0.3);">
                        <p style="color:rgba(255,255,255,0.6);font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">Nilai Akhir</p>
                        <p style="color:#93c5fd;font-size:2rem;font-weight:800;line-height:1;">{{ $nilaiAkhir['nilai_akhir'] ?? 0 }}</p>
                    </div>
                    {{-- Predikat --}}
                    <div style="background:rgba(255,255,255,0.08);border-radius:12px;padding:16px;text-align:center;border:1px solid rgba(255,255,255,0.12);">
                        <p style="color:rgba(255,255,255,0.6);font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">Predikat</p>
                        <p class="text-{{ ($nilaiAkhir['predikat_color'] ?? 'gray') }}-400" style="font-size:1rem;font-weight:700;line-height:1.3;">
                            {{ $nilaiAkhir['predikat'] ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====== PENILAIAN HASIL KERJA ====== --}}
        <div class="rounded-2xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden">
            {{-- Header --}}
            <div style="padding:20px 24px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:12px;">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#2563eb,#3b82f6);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" style="width:18px;height:18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </div>
                <div>
                    <h3 style="font-size:1rem;font-weight:700;color:#111827;margin:0;">Penilaian Hasil Kerja</h3>
                    <p style="font-size:0.75rem;color:#6b7280;margin-top:2px;">Bobot 60% &bull; Target per bulan</p>
                </div>
            </div>

            <form wire:submit="simpanPenilaianHasil">
                <div style="padding:20px 24px;display:flex;flex-direction:column;gap:16px;" class="bg-gray-50/50 dark:bg-gray-950/20">
                    @forelse($indikators as $indikator)
                        @php
                            $totalRealisasi = collect($indikator['realisasi_kinerja'] ?? [])->sum('jumlah_realisasi');
                            $target = $indikator['target_bulanan'] ?? 0;
                            $capaian = $target > 0 ? round(($totalRealisasi / $target) * 100, 1) : 0;
                            $capColor = $capaian >= 100 ? '#16a34a' : ($capaian >= 50 ? '#d97706' : '#dc2626');
                            $capBg = $capaian >= 100 ? '#f0fdf4' : ($capaian >= 50 ? '#fffbeb' : '#fef2f2');
                            $capBorder = $capaian >= 100 ? '#bbf7d0' : ($capaian >= 50 ? '#fde68a' : '#fecaca');
                        @endphp
                        <div style="border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;">
                            {{-- Card Header: Info Row --}}
                            <div style="padding:16px 20px;background:linear-gradient(to bottom,#f9fafb,#fff);">
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:12px;">
                                    <h4 style="font-size:0.9rem;font-weight:600;color:#1f2937;margin:0;">{{ $indikator['nama_indikator'] }}</h4>
                                    {{-- Capaian Badge --}}
                                    <span style="background:{{ $capBg }};color:{{ $capColor }};border:1px solid {{ $capBorder }};font-size:0.8rem;font-weight:700;padding:4px 12px;border-radius:20px;white-space:nowrap;">
                                        {{ $capaian }}%
                                    </span>
                                </div>

                                {{-- Stats Row --}}
                                <div style="display:flex;gap:24px;align-items:center;flex-wrap:wrap;">
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <span style="font-size:0.7rem;color:#9ca3af;text-transform:uppercase;font-weight:600;">Satuan</span>
                                        <span style="font-size:0.8rem;color:#374151;font-weight:500;">{{ $indikator['satuan'] }}</span>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <span style="font-size:0.7rem;color:#9ca3af;text-transform:uppercase;font-weight:600;">Target/Bln</span>
                                        <span style="font-size:0.8rem;color:#374151;font-weight:700;">{{ $target }}</span>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <span style="font-size:0.7rem;color:#9ca3af;text-transform:uppercase;font-weight:600;">Realisasi</span>
                                        <span style="font-size:0.8rem;color:#374151;font-weight:700;">{{ $totalRealisasi }}</span>
                                    </div>
                                    {{-- Progress Bar --}}
                                    <div style="flex:1;min-width:100px;">
                                        <div style="width:100%;background:#e5e7eb;border-radius:9999px;height:6px;overflow:hidden;">
                                            <div style="height:6px;border-radius:9999px;background:{{ $capColor }};width:{{ min($capaian, 100) }}%;transition:width 0.5s ease;"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bukti Dukung --}}
                                @if(count($indikator['bukti_dukung'] ?? []) > 0)
                                    <div style="margin-top:10px;display:flex;flex-wrap:wrap;gap:6px;">
                                        @foreach($indikator['bukti_dukung'] as $bukti)
                                            <a href="{{ $bukti['link_bukti'] }}" target="_blank"
                                               style="display:inline-flex;align-items:center;gap:4px;font-size:0.7rem;font-weight:500;padding:3px 10px;border-radius:20px;background:#eff6ff;color:#2563eb;text-decoration:none;">
                                                📎 {{ $bukti['judul_bukti'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            {{-- Card Bottom: Feedback Only with Quick Emoji Buttons --}}
                            <div style="border-top:1px solid #f3f4f6;padding:16px 20px;">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;flex-wrap:wrap;gap:8px;">
                                    <label style="font-size:0.7rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:0.03em;margin:0;">Feedback</label>
                                    <div style="display:flex;gap:6px;align-items:center;">
                                        <span style="font-size:0.7rem;color:#9ca3af;font-weight:500;">Feedback Cepat:</span>
                                        <button type="button" 
                                                wire:click="$set('feedbacks.{{ $indikator['id'] }}', '👎 Di Bawah Ekspektasi')" 
                                                style="padding:3px 8px;font-size:0.75rem;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;border-radius:6px;cursor:pointer;font-weight:600;transition:all 0.2s;">
                                            👎 Di Bawah Ekspektasi
                                        </button>
                                        <button type="button" 
                                                wire:click="$set('feedbacks.{{ $indikator['id'] }}', '👍 Sesuai Ekspektasi')" 
                                                style="padding:3px 8px;font-size:0.75rem;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;border-radius:6px;cursor:pointer;font-weight:600;transition:all 0.2s;">
                                            👍 Sesuai Ekspektasi
                                        </button>
                                        <button type="button" 
                                                wire:click="$set('feedbacks.{{ $indikator['id'] }}', '👍👍 Di Atas Ekspektasi')" 
                                                style="padding:3px 8px;font-size:0.75rem;background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;border-radius:6px;cursor:pointer;font-weight:600;transition:all 0.2s;">
                                            👍👍 Di Atas Ekspektasi
                                        </button>
                                    </div>
                                </div>
                                <textarea wire:model="feedbacks.{{ $indikator['id'] }}"
                                          rows="2"
                                          placeholder="Tuliskan feedback..."
                                          style="width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:10px;font-size:0.85rem;color:#374151;resize:vertical;background:#fafafa;box-sizing:border-box;"></textarea>
                            </div>
                        </div>
                    @empty
                        <div style="padding:40px 20px;text-align:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d1d5db" style="width:40px;height:40px;margin:0 auto 8px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <p style="color:#9ca3af;font-size:0.875rem;">Pegawai belum memiliki indikator kinerja untuk periode ini.</p>
                        </div>
                    @endforelse

                    {{-- ====== OVERALL RATING ====== --}}
                    @if(count($indikators) > 0)
                        <div style="margin-top:20px;padding:20px 24px;background:#f8fafc;border:1px dashed #cbd5e1;border-radius:16px;display:flex;flex-direction:column;gap:12px;">
                            <div>
                                <h4 style="font-size:0.9rem;font-weight:700;color:#1e293b;margin:0;">Rating Hasil Kerja (Keseluruhan)</h4>
                                <p style="font-size:0.75rem;color:#64748b;margin-top:2px;">Pilih penilaian akhir untuk capaian hasil kerja pegawai secara keseluruhan</p>
                            </div>
                            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                                {{-- Option: Di Bawah Ekspektasi --}}
                                <div wire:click="$set('overallPenilaianHasil', 'perlu_perbaikan')" 
                                     style="flex:1;min-width:180px;cursor:pointer;margin:0;padding:16px;border-radius:12px;border:2px solid {{ $overallPenilaianHasil === 'perlu_perbaikan' ? '#ef4444' : '#e2e8f0' }};background:{{ $overallPenilaianHasil === 'perlu_perbaikan' ? '#fef2f2' : '#fff' }};text-align:center;transition:all 0.2s;box-shadow:{{ $overallPenilaianHasil === 'perlu_perbaikan' ? '0 4px 12px rgba(239,68,68,0.1)' : 'none' }};">
                                    <div style="font-size:1.8rem;margin-bottom:6px;line-height:1;">👎</div>
                                    <div style="font-size:0.85rem;font-weight:700;color:{{ $overallPenilaianHasil === 'perlu_perbaikan' ? '#991b1b' : '#475569' }};">Di Bawah Ekspektasi</div>
                                </div>

                                {{-- Option: Sesuai Ekspektasi --}}
                                <div wire:click="$set('overallPenilaianHasil', 'sesuai_ekspektasi')" 
                                     style="flex:1;min-width:180px;cursor:pointer;margin:0;padding:16px;border-radius:12px;border:2px solid {{ $overallPenilaianHasil === 'sesuai_ekspektasi' ? '#10b981' : '#e2e8f0' }};background:{{ $overallPenilaianHasil === 'sesuai_ekspektasi' ? '#ecfdf5' : '#fff' }};text-align:center;transition:all 0.2s;box-shadow:{{ $overallPenilaianHasil === 'sesuai_ekspektasi' ? '0 4px 12px rgba(16,185,129,0.1)' : 'none' }};">
                                    <div style="font-size:1.8rem;margin-bottom:6px;line-height:1;">👍</div>
                                    <div style="font-size:0.85rem;font-weight:700;color:{{ $overallPenilaianHasil === 'sesuai_ekspektasi' ? '#065f46' : '#475569' }};">Sesuai Ekspektasi</div>
                                </div>

                                {{-- Option: Di Atas Ekspektasi --}}
                                <div wire:click="$set('overallPenilaianHasil', 'di_atas_ekspektasi')" 
                                     style="flex:1;min-width:180px;cursor:pointer;margin:0;padding:16px;border-radius:12px;border:2px solid {{ $overallPenilaianHasil === 'di_atas_ekspektasi' ? '#3b82f6' : '#e2e8f0' }};background:{{ $overallPenilaianHasil === 'di_atas_ekspektasi' ? '#eff6ff' : '#fff' }};text-align:center;transition:all 0.2s;box-shadow:{{ $overallPenilaianHasil === 'di_atas_ekspektasi' ? '0 4px 12px rgba(59,130,246,0.1)' : 'none' }};">
                                    <div style="font-size:1.8rem;margin-bottom:6px;line-height:1;">👍👍</div>
                                    <div style="font-size:0.85rem;font-weight:700;color:{{ $overallPenilaianHasil === 'di_atas_ekspektasi' ? '#1e40af' : '#475569' }};">Di Atas Ekspektasi</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if(count($indikators) > 0)
                    <div style="padding:16px 24px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;background:#f9fafb;">
                        <x-filament::button type="submit" color="primary" icon="heroicon-o-check">
                            Simpan Penilaian Hasil Kerja
                        </x-filament::button>
                    </div>
                @endif
            </form>
        </div>

        {{-- ====== PENILAIAN PERILAKU KERJA ====== --}}
        <div class="rounded-2xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden">
            {{-- Header --}}
            <div style="padding:20px 24px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:12px;">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" style="width:18px;height:18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                </div>
                <div>
                    <h3 style="font-size:1rem;font-weight:700;color:#111827;margin:0;">Penilaian Perilaku Kerja – BerAKHLAK</h3>
                    <p style="font-size:0.75rem;color:#6b7280;margin-top:2px;">Bobot 40%</p>
                </div>
            </div>

            <form wire:submit="simpanPenilaianPerilaku">
                <div style="padding:20px 24px;display:flex;flex-direction:column;gap:16px;">
                    @foreach($this->getPerilakuMasters() as $pm)
                        <div style="border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;">
                            {{-- Card Top: Number + Title + Indikator + Select --}}
                            <div style="padding:16px 20px;background:linear-gradient(to bottom,#f9fafb,#fff);display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
                                <div style="display:flex;gap:12px;flex:1;">
                                    {{-- Number Badge --}}
                                    <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#6366f1);color:#fff;font-size:0.8rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 2px 4px rgba(79,70,229,0.3);">
                                        {{ $pm->urutan }}
                                    </div>
                                    <div style="flex:1;">
                                        <h4 style="font-size:0.9rem;font-weight:600;color:#1f2937;margin:0 0 8px 0;">{{ $pm->nama_perilaku }}</h4>
                                        @foreach($pm->indikator as $ind)
                                            <div style="display:flex;align-items:flex-start;gap:6px;margin-bottom:3px;">
                                                <span style="color:#a5b4fc;font-size:0.7rem;margin-top:1px;flex-shrink:0;">●</span>
                                                <span style="font-size:0.75rem;color:#6b7280;line-height:1.4;">{{ $ind->deskripsi_indikator }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                {{-- Select Dropdown --}}
                                <div style="flex-shrink:0;">
                                    <select wire:model="penilaianPerilaku.{{ $pm->id }}.nilai"
                                            style="padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:0.8rem;color:#374151;background:#fff;cursor:pointer;min-width:180px;">
                                        <option value="">-- Pilih Nilai --</option>
                                        <option value="di_atas_ekspektasi">👍👍 Di Atas Ekspektasi</option>
                                        <option value="sesuai_ekspektasi">👍 Sesuai Ekspektasi</option>
                                        <option value="perlu_perbaikan">👎 Perlu Perbaikan</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Card Bottom: Ekspektasi + Feedback --}}
                            <div style="border-top:1px solid #f3f4f6;display:grid;grid-template-columns:1fr 1fr;">
                                <div style="padding:14px 20px;border-right:1px solid #f3f4f6;">
                                    <label style="display:block;font-size:0.7rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:0.03em;margin-bottom:6px;">Ekspektasi Pimpinan</label>
                                    <textarea wire:model="penilaianPerilaku.{{ $pm->id }}.ekspektasi_pimpinan"
                                              rows="2"
                                              placeholder="Tuliskan ekspektasi..."
                                              style="width:100%;padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:0.8rem;color:#374151;resize:vertical;background:#fafafa;"></textarea>
                                </div>
                                <div style="padding:14px 20px;">
                                    <label style="display:block;font-size:0.7rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:0.03em;margin-bottom:6px;">Feedback</label>
                                    <textarea wire:model="penilaianPerilaku.{{ $pm->id }}.feedback"
                                              rows="2"
                                              placeholder="Tuliskan feedback..."
                                              style="width:100%;padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:0.8rem;color:#374151;resize:vertical;background:#fafafa;"></textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="padding:16px 24px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;background:#f9fafb;">
                    <x-filament::button type="submit" color="primary" icon="heroicon-o-check">
                        Simpan Penilaian Perilaku
                    </x-filament::button>
                </div>
            </form>
        </div>

    @else
        <div class="rounded-xl p-6 flex items-start gap-3" style="background:#fffbeb;border:1px solid #fde68a;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d97706" style="width:20px;height:20px;flex-shrink:0;margin-top:2px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
            <p class="text-amber-700 font-medium text-sm">
                Tidak ada periode penilaian yang aktif saat ini. Hubungi admin untuk mengaktifkan periode.
            </p>
        </div>
    @endif

</x-filament-panels::page>
