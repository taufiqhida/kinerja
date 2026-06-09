@php
    use Filament\Support\Facades\FilamentColor;
@endphp

<x-filament-panels::page>

    {{-- ====== STATS CARDS ====== --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

        {{-- Jumlah Bawahan --}}
        <div class="rounded-xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden">
            <div class="p-5 flex items-center gap-4">
                <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:12px;padding:12px;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" style="width:24px;height:24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Bawahan</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $jumlahBawahan }}</p>
                </div>
            </div>
            <div style="height:3px;background:linear-gradient(90deg,#3b82f6,#60a5fa);"></div>
        </div>

        {{-- Sudah Dinilai --}}
        <div class="rounded-xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden">
            <div class="p-5 flex items-center gap-4">
                <div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-radius:12px;padding:12px;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#16a34a" style="width:24px;height:24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sudah Dinilai</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-0.5">{{ $jumlahDinilai }}</p>
                </div>
            </div>
            <div style="height:3px;background:linear-gradient(90deg,#22c55e,#86efac);"></div>
        </div>

        {{-- Belum Dinilai --}}
        <div class="rounded-xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden">
            <div class="p-5 flex items-center gap-4">
                <div style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border-radius:12px;padding:12px;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d97706" style="width:24px;height:24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Belum Dinilai</p>
                    <p class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-0.5">{{ $jumlahBelumDinilai }}</p>
                </div>
            </div>
            <div style="height:3px;background:linear-gradient(90deg,#f59e0b,#fcd34d);"></div>
        </div>

        {{-- Progress Penilaian --}}
        <div class="rounded-xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden">
            <div class="p-5 flex items-center gap-4">
                <div style="background:linear-gradient(135deg,#faf5ff,#e879f9);border-radius:12px;padding:12px;flex-shrink:0;opacity:0.95;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#a21caf" style="width:24px;height:24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21a7.5 7.5 0 0 0-7.5-7.5v7.5Z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Progress Penilaian</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-0.5">
                        {{ $jumlahBawahan > 0 ? round(($jumlahDinilai / $jumlahBawahan) * 100) : 0 }}%
                    </p>
                </div>
            </div>
            <div style="height:3px;background:linear-gradient(90deg,#c084fc,#a855f7);"></div>
        </div>
    </div>

    {{-- ====== INFORMASI PENILAI ====== --}}
    @if($kepala)
        <div class="rounded-xl bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                <div style="background:linear-gradient(135deg,#f5f3ff,#ede9fe);border-radius:10px;padding:8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#7c3aed" style="width:18px;height:18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Informasi Penilai</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-x-8 gap-y-4 md:grid-cols-4">
                    <div>
                        <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Nama</p>
                        <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $kepala->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">NIP</p>
                        <p class="font-medium text-gray-700 dark:text-gray-300 text-sm">{{ $kepala->nip }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Pangkat/Golongan</p>
                        <p class="font-medium text-gray-700 dark:text-gray-300 text-sm">{{ $kepala->pangkat_golongan }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Unit Kerja</p>
                        <p class="font-medium text-gray-700 dark:text-gray-300 text-sm">{{ $kepala->unitKerja?->nama_unit }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
