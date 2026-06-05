<?php

namespace App\Filament\Kepala\Pages;

use App\Models\Notifikasi;
use Filament\Pages\Page;
use Filament\Notifications\Notification as FilamentNotification;

class DaftarNotifikasi extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationLabel = 'Notifikasi';
    protected static ?int $navigationSort = 10;
    protected static ?string $title = 'Notifikasi';
    protected string $view = 'filament.shared.pages.daftar-notifikasi';

    public function getNotifikasi()
    {
        return Notifikasi::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();
    }

    public function markAsRead(int $id): void
    {
        $notif = Notifikasi::where('id', $id)->where('user_id', auth()->id())->first();
        $notif?->markAsRead();
    }

    public function markAllAsRead(): void
    {
        Notifikasi::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        FilamentNotification::make()
            ->title('Semua notifikasi telah ditandai sebagai dibaca.')
            ->success()
            ->send();
    }

    public static function getNavigationBadge(): ?string
    {
        $count = Notifikasi::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}
