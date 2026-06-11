<?php

namespace App\Filament\Pegawai\Resources\IndikatorKinerjaResource\Pages;

use App\Filament\Pegawai\Resources\IndikatorKinerjaResource;
use App\Models\IndikatorKinerja;
use App\Models\Pegawai;
use App\Models\PeriodePenilaian;
use Filament\Resources\Pages\CreateRecord;

class CreateIndikatorKinerja extends CreateRecord
{
    protected static string $resource = IndikatorKinerjaResource::class;
    protected static ?string $title = 'Tambah Indikator Kinerja';

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Pastikan periode_id terisi — gunakan dari form atau fallback ke periode aktif
        if (empty($data['periode_id'])) {
            $periodeAktif = PeriodePenilaian::getActive();
            if (! $periodeAktif) {
                \Filament\Notifications\Notification::make()
                    ->title('Tidak ada periode aktif')
                    ->body('Tidak ada periode penilaian yang aktif. Hubungi admin.')
                    ->danger()
                    ->send();
                return parent::handleRecordCreation($data);
            }
            $data['periode_id'] = $periodeAktif->id;
        }

        // Cek apakah indikator dengan nama yang sama sudah ada di periode ini
        $existing = IndikatorKinerja::where('pegawai_id', $data['pegawai_id'])
            ->where('periode_id', $data['periode_id'])
            ->where('nama_indikator', $data['nama_indikator'])
            ->first();

        if ($existing) {
            \Filament\Notifications\Notification::make()
                ->title('Indikator sudah ada')
                ->body('Indikator dengan nama ini sudah ada di periode yang dipilih.')
                ->warning()
                ->send();
            return $existing;
        }

        // Buat HANYA untuk periode yang dipilih — tidak menyebar ke periode lain
        return IndikatorKinerja::create($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
