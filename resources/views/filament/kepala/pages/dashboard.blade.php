@php
    use Filament\Support\Facades\FilamentColor;
@endphp

<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        {{-- Periode Aktif --}}
        <div class="col-span-full rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-x-3">
                <div class="rounded-lg bg-primary-50 p-2 dark:bg-primary-500/10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:24px;height:24px;" class="text-primary-600 dark:text-primary-400"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Periode Aktif</p>
                    <p class="text-lg font-semibold text-gray-950 dark:text-white">
                        {{ $periodeAktif?->nama_periode ?? 'Tidak ada periode aktif' }}
                    </p>
                    @if($periodeAktif)
                        <p class="text-xs text-gray-400">
                            {{ $periodeAktif->tanggal_mulai->format('d M Y') }} - {{ $periodeAktif->tanggal_selesai->format('d M Y') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Jumlah Bawahan --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-x-3">
                <div class="rounded-lg bg-primary-50 p-2 dark:bg-primary-500/10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:24px;height:24px;" class="text-primary-600 dark:text-primary-400"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Bawahan</p>
                    <p class="text-3xl font-bold text-gray-950 dark:text-white">{{ $jumlahBawahan }}</p>
                </div>
            </div>
        </div>

        {{-- Sudah Dinilai --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-x-3">
                <div class="rounded-lg bg-success-50 p-2 dark:bg-success-500/10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:24px;height:24px;" class="text-success-600 dark:text-success-400"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sudah Dinilai</p>
                    <p class="text-3xl font-bold text-success-600 dark:text-success-400">{{ $jumlahDinilai }}</p>
                </div>
            </div>
        </div>

        {{-- Belum Dinilai --}}
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-x-3">
                <div class="rounded-lg bg-warning-50 p-2 dark:bg-warning-500/10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:24px;height:24px;" class="text-warning-600 dark:text-warning-400"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Belum Dinilai</p>
                    <p class="text-3xl font-bold text-warning-600 dark:text-warning-400">{{ $jumlahBelumDinilai }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($kepala)
        <div class="mt-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">Informasi Penilai</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nama</p>
                    <p class="font-medium text-gray-950 dark:text-white">{{ $kepala->nama_lengkap }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">NIP</p>
                    <p class="font-medium text-gray-950 dark:text-white">{{ $kepala->nip }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pangkat/Golongan</p>
                    <p class="font-medium text-gray-950 dark:text-white">{{ $kepala->pangkat_golongan }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Unit Kerja</p>
                    <p class="font-medium text-gray-950 dark:text-white">{{ $kepala->unitKerja?->nama_unit }}</p>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
