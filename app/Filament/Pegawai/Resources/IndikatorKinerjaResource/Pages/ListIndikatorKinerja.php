<?php

namespace App\Filament\Pegawai\Resources\IndikatorKinerjaResource\Pages;

use App\Filament\Pegawai\Resources\IndikatorKinerjaResource;
use App\Models\IndikatorKinerja;
use App\Models\Pegawai;
use App\Models\PeriodePenilaian;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListIndikatorKinerja extends ListRecords
{
    protected static string $resource = IndikatorKinerjaResource::class;
    protected static ?string $title = 'Indikator Kinerja Saya';

    protected function getHeaderActions(): array
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->first();

        return [
            Actions\Action::make('salinIndikator')
                ->label('Salin Indikator Kinerja')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->visible(fn () => $pegawai !== null)
                ->form([
                    Forms\Components\Select::make('source_periode_id')
                        ->label('Salin Dari (Periode Sumber)')
                        ->placeholder('Pilih periode sumber...')
                        ->options(function () use ($pegawai) {
                            if (! $pegawai) {
                                return [];
                            }
                            return PeriodePenilaian::whereHas('indikatorKinerja', function ($q) use ($pegawai) {
                                    $q->where('pegawai_id', $pegawai->id);
                                })
                                ->orderBy('tahun', 'desc')
                                ->orderBy('tanggal_mulai', 'desc')
                                ->pluck('nama_periode', 'id')
                                ->toArray();
                        })
                        ->required()
                        ->live()
                        ->native(false),

                    Forms\Components\CheckboxList::make('target_periode_ids')
                        ->label('Salin Ke (Periode Tujuan)')
                        ->options(function ($get) {
                            $sourceId = $get('source_periode_id');

                            $query = PeriodePenilaian::orderBy('tahun', 'desc')
                                ->orderBy('tanggal_mulai', 'desc');
                            if ($sourceId) {
                                $query->where('id', '!=', $sourceId);
                            }
                            return $query->pluck('nama_periode', 'id')->toArray();
                        })
                        ->required()
                        ->columns(2),
                ])
                ->action(function (array $data) use ($pegawai) {
                    if (! $pegawai) {
                        return;
                    }

                    $sourceId = $data['source_periode_id'];
                    $targetIds = $data['target_periode_ids'];

                    if (empty($targetIds)) {
                        Notification::make()
                            ->title('Gagal')
                            ->body('Pilih setidaknya satu periode tujuan.')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Ambil indikator dari periode sumber
                    $indikators = IndikatorKinerja::where('periode_id', $sourceId)
                        ->where('pegawai_id', $pegawai->id)
                        ->get();

                    if ($indikators->isEmpty()) {
                        Notification::make()
                            ->title('Peringatan')
                            ->body('Tidak ada indikator pada periode sumber yang dipilih.')
                            ->warning()
                            ->send();
                        return;
                    }

                    $disalinCount = 0;
                    $targetPeriodsNames = [];

                    foreach ($targetIds as $targetId) {
                        $targetPeriod = PeriodePenilaian::find($targetId);
                        if (! $targetPeriod) {
                            continue;
                        }

                        $targetPeriodsNames[] = $targetPeriod->nama_periode;

                        foreach ($indikators as $ind) {
                            $sudahAda = IndikatorKinerja::where('periode_id', $targetId)
                                ->where('pegawai_id', $pegawai->id)
                                ->where('nama_indikator', $ind->nama_indikator)
                                ->exists();

                            if (! $sudahAda) {
                                IndikatorKinerja::create([
                                    'pegawai_id'     => $pegawai->id,
                                    'periode_id'     => $targetId,
                                    'nama_indikator' => $ind->nama_indikator,
                                    'satuan'         => $ind->satuan,
                                    'target_bulanan' => $ind->target_bulanan,
                                ]);
                                $disalinCount++;
                            }
                        }
                    }

                    $targetsList = implode(', ', $targetPeriodsNames);
                    Notification::make()
                        ->title('Indikator Disalin')
                        ->body("Berhasil menyalin {$disalinCount} indikator ke periode: {$targetsList}.")
                        ->success()
                        ->send();
                }),

            Actions\CreateAction::make()->label('+ Tambah Indikator'),
        ];
    }
}
