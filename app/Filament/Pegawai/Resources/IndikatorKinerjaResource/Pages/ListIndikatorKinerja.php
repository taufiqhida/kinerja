<?php

namespace App\Filament\Pegawai\Resources\IndikatorKinerjaResource\Pages;

use App\Filament\Pegawai\Resources\IndikatorKinerjaResource;
use App\Models\IndikatorKinerja;
use App\Models\Pegawai;
use App\Models\PeriodePenilaian;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListIndikatorKinerja extends ListRecords
{
    protected static string $resource = IndikatorKinerjaResource::class;
    protected static ?string $title = 'Indikator Kinerja Saya';

    protected function getHeaderActions(): array
    {
        $pegawai     = Pegawai::where('user_id', auth()->id())->first();
        $periodeAktif = PeriodePenilaian::getActive();

        // Cari semua periode yang punya indikator untuk pegawai ini (selain periode aktif)
        $periodeLama = null;
        if ($pegawai && $periodeAktif) {
            $periodeLama = PeriodePenilaian::where('id', '!=', $periodeAktif->id)
                ->orderBy('tanggal_mulai', 'desc')
                ->get()
                ->first(fn ($p) => IndikatorKinerja::where('pegawai_id', $pegawai->id)
                    ->where('periode_id', $p->id)
                    ->exists()
                );
        }

        // Cek apakah periode aktif sudah punya indikator
        $sudahAdaIndikator = $pegawai && $periodeAktif
            ? IndikatorKinerja::where('pegawai_id', $pegawai->id)
                ->where('periode_id', $periodeAktif->id)
                ->exists()
            : false;

        $modalDesc = $periodeLama
            ? "Semua indikator dari periode \"{$periodeLama->nama_periode}\" akan disalin ke \"{$periodeAktif?->nama_periode}\". Realisasi mulai dari 0. Indikator di periode lain tidak terpengaruh."
            : 'Tidak ada periode sebelumnya yang memiliki indikator.';

        return [
            // Tombol Salin dari Periode Sebelumnya
            Actions\Action::make('salinDariPeriodeSebelumnya')
                ->label('Salin dari Periode Sebelumnya')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Salin Indikator ke Periode Ini')
                ->modalDescription($modalDesc)
                ->modalSubmitActionLabel('Ya, Salin Sekarang')
                ->visible(fn () => ! $sudahAdaIndikator && $periodeLama !== null)
                ->action(function () use ($pegawai, $periodeAktif, $periodeLama) {
                    if (! $pegawai || ! $periodeAktif || ! $periodeLama) {
                        return;
                    }

                    $indikators = IndikatorKinerja::where('periode_id', $periodeLama->id)
                        ->where('pegawai_id', $pegawai->id)
                        ->get();

                    $disalin = 0;
                    foreach ($indikators as $ind) {
                        $sudahAda = IndikatorKinerja::where('periode_id', $periodeAktif->id)
                            ->where('pegawai_id', $pegawai->id)
                            ->where('nama_indikator', $ind->nama_indikator)
                            ->exists();

                        if (! $sudahAda) {
                            IndikatorKinerja::create([
                                'pegawai_id'     => $pegawai->id,
                                'periode_id'     => $periodeAktif->id,  // ← hanya ke periode aktif ini
                                'nama_indikator' => $ind->nama_indikator,
                                'satuan'         => $ind->satuan,
                                'target_bulanan' => $ind->target_bulanan,
                            ]);
                            $disalin++;
                        }
                    }

                    Notification::make()
                        ->title('Indikator Disalin')
                        ->body("{$disalin} indikator berhasil disalin ke \"{$periodeAktif->nama_periode}\". Realisasi dimulai dari 0.")
                        ->success()
                        ->send();
                }),

            Actions\CreateAction::make()->label('+ Tambah Indikator'),
        ];
    }
}
