<x-filament-panels::page>
    <div class="flex justify-end mb-4">
        <x-filament::button wire:click="markAllAsRead" color="gray" icon="heroicon-o-check" size="sm">
            Tandai Semua Dibaca
        </x-filament::button>
    </div>

    <div class="space-y-3">
        @forelse($this->getNotifikasi() as $notif)
            <div wire:key="notif-{{ $notif->id }}"
                 class="rounded-xl p-4 shadow-sm ring-1 transition-all
                        {{ $notif->is_read
                            ? 'bg-white ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10'
                            : 'bg-primary-50 ring-primary-200 dark:bg-primary-500/10 dark:ring-primary-500/20' }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex-shrink-0">
                            @switch($notif->tipe)
                                @case('warning')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#eab308" style="width:20px;height:20px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                    </svg>
                                    @break
                                @case('success')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#22c55e" style="width:20px;height:20px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    @break
                                @case('danger')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ef4444" style="width:20px;height:20px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    @break
                                @default
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#3b82f6" style="width:20px;height:20px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                    </svg>
                            @endswitch
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-950 dark:text-white text-sm">{{ $notif->judul }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">{{ $notif->pesan }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if(! $notif->is_read)
                            <button wire:click="markAsRead({{ $notif->id }})"
                                    class="text-xs text-primary-600 hover:underline dark:text-primary-400">
                                Tandai Dibaca
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl bg-white p-8 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:48px;height:48px;color:#9ca3af;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.143 17.082a24.248 24.248 0 0 0 5.714 0m-5.714 0a1.5 1.5 0 0 1-1.488-1.285C7.404 14.102 7.101 12.37 7.101 10.5c0-2.9 2.2-5.25 4.899-5.25s4.899 2.35 4.899 5.25c0 1.87-.303 3.602-.554 5.297a1.5 1.5 0 0 1-1.488 1.285m-5.714 0H5.625a1.523 1.523 0 0 1-1.014-.39c-.263-.247-.41-.578-.41-.925 0-3.122 1.492-5.88 3.726-7.46M14.857 17.082h3.518c.378 0 .751-.143 1.014-.39.263-.247.41-.578.41-.925 0-3.122-1.492-5.88-3.726-7.46M9.143 17.082V18a2.857 2.857 0 0 0 5.714 0v-.918M3 3l18 18" />
                </svg>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Tidak ada notifikasi.</p>
            </div>
        @endforelse
    </div>
</x-filament-panels::page>
