<x-filament-panels::page>
    {{-- Export Button --}}
    <div class="flex justify-end mb-2">
        <a href="{{ route('export.hasil-penilaian', $record->id) }}"
           target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-success-600 text-white hover:bg-success-700 transition-colors shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px; flex-shrink: 0;" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            📥 Export PDF
        </a>
    </div>

    {{-- Info Pegawai --}}
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">Informasi Pegawai</h3>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Nama Lengkap</p>
                <p class="font-semibold text-gray-950 dark:text-white">{{ $record->nama_lengkap }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">NIK</p>
                <p class="font-medium text-gray-950 dark:text-white">{{ $record->nik }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Pangkat/Golongan</p>
                <p class="font-medium text-gray-950 dark:text-white">{{ $record->pangkat_golongan }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Jabatan</p>
                <p class="font-medium text-gray-950 dark:text-white">{{ $record->jabatan?->nama_jabatan }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Unit Kerja</p>
                <p class="font-medium text-gray-950 dark:text-white">{{ $record->unitKerja?->nama_unit }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Periode</p>
                <p class="font-medium text-gray-950 dark:text-white">{{ $periodeAktif?->nama_periode ?? '-' }}</p>
            </div>
        </div>
    </div>

    @if($periodeAktif)
        {{-- Nilai Akhir Summary --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">Ringkasan Nilai</h3>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                <div class="text-center p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Nilai Hasil Kerja</p>
                    <p class="text-2xl font-bold text-gray-950 dark:text-white">{{ $nilaiAkhir['nilai_hasil_kerja'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400">× 60% = {{ $nilaiAkhir['nilai_hasil_kerja_bobot'] ?? 0 }}</p>
                </div>
                <div class="text-center p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Nilai Perilaku</p>
                    <p class="text-2xl font-bold text-gray-950 dark:text-white">{{ $nilaiAkhir['nilai_perilaku_kerja'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400">× 40% = {{ $nilaiAkhir['nilai_perilaku_kerja_bobot'] ?? 0 }}</p>
                </div>
                <div class="text-center p-3 rounded-lg bg-primary-50 dark:bg-primary-500/10">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Nilai Akhir</p>
                    <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ $nilaiAkhir['nilai_akhir'] ?? 0 }}</p>
                </div>
                <div class="text-center p-3 rounded-lg bg-{{ ($nilaiAkhir['predikat_color'] ?? 'gray') }}-50 dark:bg-{{ ($nilaiAkhir['predikat_color'] ?? 'gray') }}-500/10 col-span-2 md:col-span-2">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Predikat</p>
                    <p class="text-2xl font-bold text-{{ ($nilaiAkhir['predikat_color'] ?? 'gray') }}-600 dark:text-{{ ($nilaiAkhir['predikat_color'] ?? 'gray') }}-400">
                        {{ $nilaiAkhir['predikat'] ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- SECTION 1: Penilaian Hasil Kerja --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:20px;height:20px;flex-shrink:0;" class="text-primary-600"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                    Penilaian Hasil Kerja (Bobot 60%)
                </span>
            </h3>

            <form wire:submit="simpanPenilaianHasil">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left py-3 px-2 font-medium text-gray-500 dark:text-gray-400">Indikator Kinerja</th>
                                <th class="text-center py-3 px-2 font-medium text-gray-500 dark:text-gray-400">Satuan</th>
                                <th class="text-center py-3 px-2 font-medium text-gray-500 dark:text-gray-400">Target</th>
                                <th class="text-center py-3 px-2 font-medium text-gray-500 dark:text-gray-400">Realisasi</th>
                                <th class="text-center py-3 px-2 font-medium text-gray-500 dark:text-gray-400">Capaian</th>
                                <th class="text-center py-3 px-2 font-medium text-gray-500 dark:text-gray-400">Penilaian</th>
                                <th class="text-left py-3 px-2 font-medium text-gray-500 dark:text-gray-400">Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($indikators as $indikator)
                                @php
                                    $totalRealisasi = collect($indikator['realisasi_kinerja'] ?? [])->sum('jumlah_realisasi');
                                    $target = $indikator['target_tahunan'] ?? 0;
                                    $capaian = $target > 0 ? round(($totalRealisasi / $target) * 100, 1) : 0;
                                @endphp
                                <tr class="border-b border-gray-100 dark:border-gray-800">
                                    <td class="py-3 px-2">
                                        <p class="font-medium text-gray-950 dark:text-white">{{ $indikator['nama_indikator'] }}</p>
                                        @if(count($indikator['bukti_dukung'] ?? []) > 0)
                                            <div class="mt-1">
                                                @foreach($indikator['bukti_dukung'] as $bukti)
                                                    <a href="{{ $bukti['link_bukti'] }}" target="_blank" class="text-xs text-primary-600 hover:underline mr-2">
                                                        📎 {{ $bukti['judul_bukti'] }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-2 text-center text-gray-600 dark:text-gray-400">{{ $indikator['satuan'] }}</td>
                                    <td class="py-3 px-2 text-center font-medium text-gray-950 dark:text-white">{{ $target }}</td>
                                    <td class="py-3 px-2 text-center font-medium text-gray-950 dark:text-white">{{ $totalRealisasi }}</td>
                                    <td class="py-3 px-2 text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="font-semibold {{ $capaian >= 100 ? 'text-success-600' : ($capaian >= 50 ? 'text-warning-600' : 'text-danger-600') }}">
                                                {{ $capaian }}%
                                            </span>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
                                                <div class="h-1.5 rounded-full {{ $capaian >= 100 ? 'bg-success-500' : ($capaian >= 50 ? 'bg-warning-500' : 'bg-danger-500') }}"
                                                     style="width: {{ min($capaian, 100) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2 text-center">
                                        <select wire:model="penilaianHasil.{{ $indikator['id'] }}"
                                                class="rounded-lg border-gray-300 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">
                                            <option value="">-- Pilih --</option>
                                            <option value="di_atas_ekspektasi">👍👍 Di Atas Ekspektasi</option>
                                            <option value="sesuai_ekspektasi">👍 Sesuai Ekspektasi</option>
                                            <option value="perlu_perbaikan">👎 Perlu Perbaikan</option>
                                        </select>
                                    </td>
                                    <td class="py-3 px-2">
                                        <textarea wire:model="feedbacks.{{ $indikator['id'] }}"
                                                  rows="2"
                                                  placeholder="Feedback..."
                                                  class="w-full rounded-lg border-gray-300 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300"></textarea>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                        Pegawai belum memiliki indikator kinerja untuk periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($indikators) > 0)
                    <div class="mt-4 flex justify-end">
                        <x-filament::button type="submit" color="primary" icon="heroicon-o-check">
                            Simpan Penilaian Hasil Kerja
                        </x-filament::button>
                    </div>
                @endif
            </form>
        </div>

        {{-- SECTION 2: Penilaian Perilaku Kerja --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:20px;height:20px;flex-shrink:0;" class="text-primary-600"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                    Penilaian Perilaku Kerja - BerAKHLAK (Bobot 40%)
                </span>
            </h3>

            <form wire:submit="simpanPenilaianPerilaku">
                <div class="space-y-4">
                    @foreach($this->getPerilakuMasters() as $pm)
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                            <div class="flex items-start justify-between gap-4 mb-3">
                                <div>
                                    <h4 class="font-semibold text-gray-950 dark:text-white">
                                        {{ $pm->urutan }}. {{ $pm->nama_perilaku }}
                                    </h4>
                                    <ul class="mt-1 text-xs text-gray-500 dark:text-gray-400 list-disc list-inside">
                                        @foreach($pm->indikator as $ind)
                                            <li>{{ $ind->deskripsi_indikator }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="flex-shrink-0">
                                    <select wire:model="penilaianPerilaku.{{ $pm->id }}.nilai"
                                            class="rounded-lg border-gray-300 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">
                                        <option value="">-- Pilih Nilai --</option>
                                        <option value="di_atas_ekspektasi">👍👍 Di Atas Ekspektasi</option>
                                        <option value="sesuai_ekspektasi">👍 Sesuai Ekspektasi</option>
                                        <option value="perlu_perbaikan">👎 Perlu Perbaikan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Ekspektasi Pimpinan</label>
                                    <textarea wire:model="penilaianPerilaku.{{ $pm->id }}.ekspektasi_pimpinan"
                                              rows="2"
                                              placeholder="Tuliskan ekspektasi..."
                                              class="w-full mt-1 rounded-lg border-gray-300 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300"></textarea>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Feedback</label>
                                    <textarea wire:model="penilaianPerilaku.{{ $pm->id }}.feedback"
                                              rows="2"
                                              placeholder="Tuliskan feedback..."
                                              class="w-full mt-1 rounded-lg border-gray-300 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300"></textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 flex justify-end">
                    <x-filament::button type="submit" color="primary" icon="heroicon-o-check">
                        Simpan Penilaian Perilaku
                    </x-filament::button>
                </div>
            </form>
        </div>
    @else
        <div class="rounded-xl bg-warning-50 p-6 dark:bg-warning-500/10">
            <p class="text-warning-600 dark:text-warning-400 font-medium">
                ⚠️ Tidak ada periode penilaian yang aktif saat ini. Hubungi admin untuk mengaktifkan periode.
            </p>
        </div>
    @endif
</x-filament-panels::page>
