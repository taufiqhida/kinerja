<x-filament-panels::page>
    <div class="mb-4">
        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter Periode:</label>
        <select wire:model.live="selectedPeriodeId"
                class="ml-2 rounded-lg border-gray-300 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">
            <option value="0">-- Pilih Periode --</option>
            @foreach($periodeOptions as $id => $nama)
                <option value="{{ $id }}">{{ $nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800">
                        <th class="py-3 px-4 text-center font-semibold text-gray-600 dark:text-gray-300">Rank</th>
                        <th class="py-3 px-4 text-left font-semibold text-gray-600 dark:text-gray-300">Nama</th>
                        <th class="py-3 px-4 text-left font-semibold text-gray-600 dark:text-gray-300">Jabatan</th>
                        <th class="py-3 px-4 text-left font-semibold text-gray-600 dark:text-gray-300">Unit Kerja</th>
                        <th class="py-3 px-4 text-center font-semibold text-gray-600 dark:text-gray-300">Hasil (60%)</th>
                        <th class="py-3 px-4 text-center font-semibold text-gray-600 dark:text-gray-300">Perilaku (40%)</th>
                        <th class="py-3 px-4 text-center font-semibold text-gray-600 dark:text-gray-300">Nilai Akhir</th>
                        <th class="py-3 px-4 text-center font-semibold text-gray-600 dark:text-gray-300">Predikat</th>
                        <th class="py-3 px-4 text-center font-semibold text-gray-600 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ranking as $item)
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="py-3 px-4 text-center">
                                @if($item['ranking'] <= 3)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ match($item['ranking']) { 1 => 'bg-amber-100 text-amber-700', 2 => 'bg-gray-100 text-gray-600', 3 => 'bg-orange-100 text-orange-700' } }} font-bold text-sm">
                                        {{ $item['ranking'] }}
                                    </span>
                                @else
                                    <span class="text-gray-500">{{ $item['ranking'] }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-medium text-gray-950 dark:text-white">{{ $item['nama_lengkap'] }}</td>
                            <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ $item['jabatan'] ?? '-' }}</td>
                            <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ $item['unit_kerja'] ?? '-' }}</td>
                            <td class="py-3 px-4 text-center text-gray-950 dark:text-white">
                                <span class="font-medium">{{ $item['nilai_hasil_kerja'] }}</span>
                                <span class="text-xs text-gray-400 block">({{ $item['nilai_hasil_kerja_bobot'] }})</span>
                            </td>
                            <td class="py-3 px-4 text-center text-gray-950 dark:text-white">
                                <span class="font-medium">{{ $item['nilai_perilaku_kerja'] }}</span>
                                <span class="text-xs text-gray-400 block">({{ $item['nilai_perilaku_kerja_bobot'] }})</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="text-lg font-bold text-primary-600 dark:text-primary-400">
                                    {{ $item['nilai_akhir'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ match($item['predikat_color']) {
                                        'success' => 'bg-success-100 text-success-700 dark:bg-success-500/10 dark:text-success-400',
                                        'info' => 'bg-info-100 text-info-700 dark:bg-info-500/10 dark:text-info-400',
                                        'warning' => 'bg-warning-100 text-warning-700 dark:bg-warning-500/10 dark:text-warning-400',
                                        'danger' => 'bg-danger-100 text-danger-700 dark:bg-danger-500/10 dark:text-danger-400',
                                        default => 'bg-gray-100 text-gray-700'
                                    } }}">
                                    {{ $item['predikat'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('export.hasil-penilaian', ['pegawaiId' => $item['pegawai_id'], 'periode_id' => $selectedPeriodeId]) }}"
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-success-50 text-success-700 hover:bg-success-100 dark:bg-success-500/10 dark:text-success-400 dark:hover:bg-success-500/20 transition-colors"
                                   target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                @if($selectedPeriodeId == 0)
                                    Pilih periode untuk melihat rekap penilaian.
                                @else
                                    Belum ada data penilaian untuk periode ini.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
