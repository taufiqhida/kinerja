<x-filament-panels::page>
    @if(! $periodeAktif)
        <div class="rounded-xl p-6" style="background-color: #fefce8;">
            <p style="color: #ca8a04; font-weight: 500;">
                ⚠️ Tidak ada periode penilaian yang aktif saat ini.
            </p>
        </div>
    @elseif(count($indikators) === 0)
        <div class="rounded-xl p-6" style="background-color: #eff6ff;">
            <p style="color: #2563eb; font-weight: 500;">
                ℹ️ Anda belum memiliki indikator kinerja. Tambahkan melalui menu Indikator Kinerja terlebih dahulu.
            </p>
        </div>
    @else
        {{-- Export Button --}}
        <div class="flex justify-end mb-2">
            <x-filament::button color="success" icon="heroicon-o-arrow-down-tray" wire:click="exportPdf">
                📥 Export PDF
            </x-filament::button>
        </div>

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
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tampilkan dan input realisasi kinerja pada periode yang dipilih.</p>
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

        {{-- ===== SECTION 1: INPUT REALISASI KINERJA ===== --}}
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:20px;height:20px;flex-shrink:0;" class="text-primary-600"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" /></svg>
                Input Realisasi Kinerja
            </h3>

            <div class="space-y-6">
                @foreach($indikators as $indikator)
                    @php
                        $totalRealisasi = collect($indikator['realisasi_kinerja'] ?? [])->sum('jumlah_realisasi');
                        $target = $indikator['target_bulanan'] ?? 0;
                        $capaian = $target > 0 ? round(($totalRealisasi / $target) * 100, 1) : 0;
                        $progressColor = $capaian >= 100 ? '#22c55e' : ($capaian >= 50 ? '#eab308' : '#ef4444');
                        $penilaian = $indikator['penilaian_hasil'] ?? null;
                        $nilaiLabel = $penilaian ? (\App\Models\PenilaianHasil::NILAI_LABEL[$penilaian['nilai']] ?? '-') : 'Belum Dinilai';
                        $nilaiEmoji = $penilaian ? (\App\Models\PenilaianHasil::NILAI_EMOJI[$penilaian['nilai']] ?? '') : '';
                        $feedbackList = $indikator['feedback_hasil'] ?? [];
                    @endphp

                    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
                        {{-- Header --}}
                        <div class="p-5 border-b border-gray-100 dark:border-gray-800">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                <div>
                                    <h4 class="font-semibold text-gray-950 dark:text-white text-base">{{ $indikator['nama_indikator'] }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                        Satuan: <strong>{{ $indikator['satuan'] }}</strong> &bull; Target: <strong>{{ $target }}</strong>
                                    </p>
                                </div>
                                <div class="flex items-center gap-4">
                                    {{-- Progress --}}
                                    <div style="min-width: 160px;">
                                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                                            <span class="text-sm text-gray-500">{{ $totalRealisasi }}/{{ $target }}</span>
                                            <span class="text-sm font-bold" style="color:{{ $progressColor }}">{{ $capaian }}%</span>
                                        </div>
                                        <div style="width:100%;background:#e5e7eb;border-radius:9999px;height:8px;overflow:hidden;">
                                            <div style="height:8px;border-radius:9999px;background:{{ $progressColor }};width:{{ min($capaian, 100) }}%;transition:width 0.5s ease;"></div>
                                        </div>
                                    </div>
                                    {{-- Penilaian Atasan Badge --}}
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $penilaian ? match($penilaian['nilai']) {
                                            'di_atas_ekspektasi' => 'bg-success-100 text-success-700 dark:bg-success-500/10 dark:text-success-400',
                                            'sesuai_ekspektasi' => 'bg-info-100 text-info-700 dark:bg-info-500/10 dark:text-info-400',
                                            'perlu_perbaikan' => 'bg-danger-100 text-danger-700 dark:bg-danger-500/10 dark:text-danger-400',
                                            default => 'bg-gray-100 text-gray-700'
                                        } : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400' }}">
                                        {{ $nilaiEmoji }} {{ $nilaiLabel }}
                                    </span>
                                    <div class="flex gap-2">
                                        <x-filament::button size="sm" color="primary" icon="heroicon-o-plus"
                                                            wire:click="openTambahRealisasi({{ $indikator['id'] }})">
                                            Tambah Realisasi
                                        </x-filament::button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Form Tambah Realisasi (inline) --}}
                        @if($selectedIndikatorId === $indikator['id'])
                            <div class="p-5 border-b border-gray-100 dark:border-gray-800" style="background-color:#f0f9ff;">
                                <h5 style="font-weight:600;font-size:0.875rem;color:#0369a1;margin-bottom:12px;">
                                    ➕ Tambah Realisasi
                                </h5>
                                <form wire:submit="simpanRealisasi">
                                    <div style="display:grid;grid-template-columns:1fr 1fr 2fr;gap:12px;align-items:end;">
                                        <div>
                                            <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;margin-bottom:4px;">Jumlah Realisasi *</label>
                                            <input type="number" wire:model="jumlahRealisasi" min="1"
                                                   style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;background:#fff;" />
                                            @error('jumlahRealisasi') <span style="font-size:0.75rem;color:#ef4444;">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;margin-bottom:4px;">Tanggal Realisasi *</label>
                                            <input type="date" wire:model="tanggalRealisasi"
                                                   min="{{ $minTanggalRealisasi }}"
                                                   max="{{ $maxTanggalRealisasi }}"
                                                   style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;background:#fff;" />
                                            @error('tanggalRealisasi') <span style="font-size:0.75rem;color:#ef4444;">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;margin-bottom:4px;">Keterangan (opsional)</label>
                                            <input type="text" wire:model="keterangan" placeholder="Catatan tambahan..."
                                                   style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;background:#fff;" />
                                        </div>
                                    </div>
                                    {{-- Bukti Dukung (opsional) --}}
                                    <div style="margin-top:12px;padding-top:12px;border-top:1px dashed #d1d5db;">
                                        <p style="font-size:0.75rem;font-weight:600;color:#15803d;margin-bottom:8px;">📎 Bukti Dukung (opsional)</p>
                                        <div style="display:grid;grid-template-columns:1fr 2fr;gap:12px;align-items:end;">
                                            <div>
                                                <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;margin-bottom:4px;">Judul Bukti</label>
                                                <input type="text" wire:model="judulBukti" placeholder="e.g. Laporan Bulanan Januari"
                                                       style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;background:#fff;" />
                                                @error('judulBukti') <span style="font-size:0.75rem;color:#ef4444;">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label style="display:block;font-size:0.75rem;font-weight:500;color:#374151;margin-bottom:4px;">Link Bukti (URL)</label>
                                                <input type="url" wire:model="linkBukti" placeholder="https://..."
                                                       style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;background:#fff;" />
                                                @error('linkBukti') <span style="font-size:0.75rem;color:#ef4444;">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:12px;">
                                        <button type="button" wire:click="$set('selectedIndikatorId', null)"
                                                style="padding:8px 16px;border-radius:8px;font-size:0.875rem;font-weight:500;background:#f3f4f6;color:#374151;border:1px solid #d1d5db;cursor:pointer;">
                                            Batal
                                        </button>
                                        <button type="submit"
                                                style="padding:8px 16px;border-radius:8px;font-size:0.875rem;font-weight:500;background:#4f46e5;color:#fff;border:none;cursor:pointer;">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif


                        {{-- Realisasi History --}}
                        @if(count($indikator['realisasi_kinerja'] ?? []) > 0)
                            <div class="p-4">
                                <p style="font-size:0.7rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">Riwayat Realisasi</p>
                                <div class="space-y-2">
                                    @foreach($indikator['realisasi_kinerja'] as $realisasi)
                                        <div class="flex items-center justify-between rounded-lg bg-gray-50 dark:bg-gray-800 px-3 py-2">
                                            <div style="display:flex;align-items:center;gap:12px;">
                                                <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:50%;background:#e0e7ff;color:#4338ca;font-size:0.875rem;font-weight:700;">
                                                    {{ $realisasi['jumlah_realisasi'] }}
                                                </span>
                                                <div>
                                                    <p class="text-sm text-gray-950 dark:text-white">
                                                        {{ $realisasi['keterangan'] ?: 'Tanpa keterangan' }}
                                                    </p>
                                                    <p class="text-xs text-gray-400">
                                                        {{ \Carbon\Carbon::parse($realisasi['tanggal_realisasi'])->timezone(config('app.timezone'))->format('d M Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <button wire:click="hapusRealisasi({{ $realisasi['id'] }})"
                                                    wire:confirm="Yakin hapus realisasi ini?"
                                                    style="color:#ef4444;cursor:pointer;background:none;border:none;padding:4px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Bukti Dukung --}}
                        @if(count($indikator['bukti_dukung'] ?? []) > 0)
                            <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                                <p style="font-size:0.7rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">Bukti Dukung</p>
                                <div style="display:flex;flex-wrap:wrap;gap:8px;">
                                    @foreach($indikator['bukti_dukung'] as $bukti)
                                        <div style="display:inline-flex;align-items:center;gap:8px;background:#eff6ff;border-radius:8px;padding:6px 12px;">
                                            <a href="{{ $bukti['link_bukti'] }}" target="_blank"
                                               style="font-size:0.875rem;color:#2563eb;text-decoration:none;">
                                                📎 {{ $bukti['judul_bukti'] }}
                                            </a>
                                            <button wire:click="hapusBukti({{ $bukti['id'] }})"
                                                    wire:confirm="Yakin hapus bukti ini?"
                                                    style="color:#ef4444;cursor:pointer;background:none;border:none;padding:2px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:12px;height:12px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Feedback dari Atasan --}}
                        @if(count($feedbackList) > 0)
                            <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                                <p style="font-size:0.7rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">Feedback Atasan</p>
                                <div class="pl-4 border-l-2 border-primary-200 dark:border-primary-700">
                                    @foreach($feedbackList as $fb)
                                        <div class="mb-2">
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                Feedback dari {{ $fb['kepala']['nama_lengkap'] ?? 'Atasan' }}:
                                            </p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $fb['isi_feedback'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ===== SECTION 2: HASIL PENILAIAN ===== --}}
        @if($sudahDinilai)
            {{-- Ringkasan Nilai Akhir --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mb-6">
                <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:20px;height:20px;flex-shrink:0;" class="text-primary-600"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                        Ringkasan Nilai Akhir
                    </span>
                </h3>
                <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                    <div class="text-center p-4 rounded-lg bg-gray-50 dark:bg-gray-800">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Nilai Hasil Kerja</p>
                        <p class="text-3xl font-bold text-gray-950 dark:text-white">{{ $nilaiAkhir['nilai_hasil_kerja'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">× 60% = {{ $nilaiAkhir['nilai_hasil_kerja_bobot'] ?? 0 }}</p>
                    </div>
                    <div class="text-center p-4 rounded-lg bg-gray-50 dark:bg-gray-800">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Nilai Perilaku</p>
                        <p class="text-3xl font-bold text-gray-950 dark:text-white">{{ $nilaiAkhir['nilai_perilaku_kerja'] ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">× 40% = {{ $nilaiAkhir['nilai_perilaku_kerja_bobot'] ?? 0 }}</p>
                    </div>
                    <div class="text-center p-4 rounded-lg bg-primary-50 dark:bg-primary-500/10">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Nilai Akhir</p>
                        <p class="text-3xl font-bold text-primary-600 dark:text-primary-400">{{ $nilaiAkhir['nilai_akhir'] ?? 0 }}</p>
                    </div>
                    <div class="text-center p-4 rounded-lg col-span-2 bg-{{ ($nilaiAkhir['predikat_color'] ?? 'gray') }}-50 dark:bg-{{ ($nilaiAkhir['predikat_color'] ?? 'gray') }}-500/10">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Predikat</p>
                        <p class="text-3xl font-bold text-{{ ($nilaiAkhir['predikat_color'] ?? 'gray') }}-600 dark:text-{{ ($nilaiAkhir['predikat_color'] ?? 'gray') }}-400">
                            {{ $nilaiAkhir['predikat'] ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Detail Penilaian Perilaku Kerja --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:20px;height:20px;flex-shrink:0;" class="text-primary-600"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                        Detail Penilaian Perilaku Kerja (BerAKHLAK)
                    </span>
                </h3>

                <div class="space-y-4">
                    @foreach($penilaianPerilaku as $pp)
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-950 dark:text-white">
                                        {{ $pp['urutan'] }}. {{ $pp['nama_perilaku'] }}
                                    </h4>
                                    <ul class="mt-1 text-xs text-gray-500 dark:text-gray-400 list-disc list-inside">
                                        @foreach($pp['indikator'] as $ind)
                                            <li>{{ $ind['deskripsi_indikator'] }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $pp['nilai'] ? match($pp['nilai']) {
                                            'di_atas_ekspektasi' => 'bg-success-100 text-success-700 dark:bg-success-500/10 dark:text-success-400',
                                            'sesuai_ekspektasi' => 'bg-info-100 text-info-700 dark:bg-info-500/10 dark:text-info-400',
                                            'perlu_perbaikan' => 'bg-danger-100 text-danger-700 dark:bg-danger-500/10 dark:text-danger-400',
                                            default => 'bg-gray-100 text-gray-700'
                                        } : 'bg-gray-100 text-gray-500' }}">
                                        {{ $pp['nilai_emoji'] }} {{ $pp['nilai_label'] }}
                                    </span>
                                </div>
                            </div>

                            @if(! empty($pp['ekspektasi_pimpinan']) || ! empty($pp['feedback']))
                                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @if(! empty($pp['ekspektasi_pimpinan']))
                                        <div class="pl-4 border-l-2 border-info-200 dark:border-info-700">
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Ekspektasi Pimpinan:</p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $pp['ekspektasi_pimpinan'] }}</p>
                                        </div>
                                    @endif
                                    @if(! empty($pp['feedback']))
                                        <div class="pl-4 border-l-2 border-primary-200 dark:border-primary-700">
                                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Feedback:</p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $pp['feedback'] }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <p class="text-xs text-gray-400 mt-2">Penilai: {{ $pp['penilai'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            {{-- Belum Dinilai Info --}}
            <div class="rounded-xl p-6" style="background-color: #eff6ff;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#3b82f6" style="width: 24px; height: 24px; flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p style="color: #2563eb; font-weight: 500; margin: 0;">
                        Penilaian belum dilakukan oleh atasan Anda. Hasil penilaian akan muncul di sini setelah atasan menyelesaikan penilaian.
                    </p>
                </div>
            </div>
        @endif
    @endif
</x-filament-panels::page>
