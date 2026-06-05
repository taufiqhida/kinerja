<x-filament-panels::page>

    {{-- ====== HEADER BAR: Export + Title ====== --}}
    <div class="flex items-center justify-between mb-1">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Detail penilaian kinerja pegawai</p>
        </div>
        <a href="{{ route('export.hasil-penilaian', $record->id) }}"
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
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:10px;padding:8px;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" style="width:18px;height:18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Penilaian Hasil Kerja</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Bobot 60% &bull; Target per bulan</p>
                </div>
            </div>

            <form wire:submit="simpanPenilaianHasil">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#f8faff;" class="dark:bg-gray-800/50">
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wide">Indikator Kinerja</th>
                                <th class="text-center py-3 px-3 font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wide">Satuan</th>
                                <th class="text-center py-3 px-3 font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wide">Target/Bln</th>
                                <th class="text-center py-3 px-3 font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wide">Realisasi</th>
                                <th class="text-center py-3 px-3 font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wide">Capaian</th>
                                <th class="text-center py-3 px-3 font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wide">Penilaian</th>
                                <th class="text-left py-3 px-3 font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wide">Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($indikators as $indikator)
                                @php
                                    $totalRealisasi = collect($indikator['realisasi_kinerja'] ?? [])->sum('jumlah_realisasi');
                                    $target = $indikator['target_bulanan'] ?? 0;
                                    $capaian = $target > 0 ? round(($totalRealisasi / $target) * 100, 1) : 0;
                                    $capColor = $capaian >= 100 ? '#16a34a' : ($capaian >= 50 ? '#d97706' : '#dc2626');
                                    $capBg = $capaian >= 100 ? '#f0fdf4' : ($capaian >= 50 ? '#fffbeb' : '#fef2f2');
                                @endphp
                                <tr class="border-t border-gray-100 dark:border-gray-800 hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                    <td class="py-4 px-4">
                                        <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $indikator['nama_indikator'] }}</p>
                                        @if(count($indikator['bukti_dukung'] ?? []) > 0)
                                            <div class="mt-1.5 flex flex-wrap gap-1">
                                                @foreach($indikator['bukti_dukung'] as $bukti)
                                                    <a href="{{ $bukti['link_bukti'] }}" target="_blank"
                                                       class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full"
                                                       style="background:#eff6ff;color:#2563eb;text-decoration:none;">
                                                        📎 {{ $bukti['judul_bukti'] }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-3 text-center">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $indikator['satuan'] }}</span>
                                    </td>
                                    <td class="py-4 px-3 text-center">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $target }}</span>
                                    </td>
                                    <td class="py-4 px-3 text-center">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $totalRealisasi }}</span>
                                    </td>
                                    <td class="py-4 px-3 text-center">
                                        <div class="flex flex-col items-center gap-1.5">
                                            <span class="text-sm font-bold px-2 py-0.5 rounded-full" style="background:{{ $capBg }};color:{{ $capColor }};">
                                                {{ $capaian }}%
                                            </span>
                                            <div style="width:60px;background:#e5e7eb;border-radius:9999px;height:5px;overflow:hidden;">
                                                <div style="height:5px;border-radius:9999px;background:{{ $capColor }};width:{{ min($capaian, 100) }}%;transition:width 0.5s ease;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-3 text-center">
                                        <select wire:model="penilaianHasil.{{ $indikator['id'] }}"
                                                class="rounded-lg border-gray-300 text-xs dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 py-1.5">
                                            <option value="">-- Pilih --</option>
                                            <option value="di_atas_ekspektasi">👍👍 Di Atas Ekspektasi</option>
                                            <option value="sesuai_ekspektasi">👍 Sesuai Ekspektasi</option>
                                            <option value="perlu_perbaikan">👎 Perlu Perbaikan</option>
                                        </select>
                                    </td>
                                    <td class="py-4 px-3">
                                        <textarea wire:model="feedbacks.{{ $indikator['id'] }}"
                                                  rows="2"
                                                  placeholder="Feedback..."
                                                  class="w-full rounded-lg border-gray-300 text-xs dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300"></textarea>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d1d5db" style="width:40px;height:40px;margin:0 auto 8px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                        <p class="text-gray-400 dark:text-gray-500 text-sm">Pegawai belum memiliki indikator kinerja untuk periode ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($indikators) > 0)
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex justify-end"
                         style="background:#f8faff;">
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
