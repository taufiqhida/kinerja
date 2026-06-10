<?php

namespace App\Filament\Pegawai\Resources\IndikatorKinerjaResource\Pages;

use App\Filament\Pegawai\Resources\IndikatorKinerjaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIndikatorKinerja extends EditRecord
{
    protected static string $resource = IndikatorKinerjaResource::class;
    protected static ?string $title = 'Edit Indikator Kinerja';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $activePeriodIds = \App\Models\PeriodePenilaian::where('is_active', true)->pluck('id')->toArray();
        $originalName = $record->getOriginal('nama_indikator');
        
        // Update this record
        $record->update($data);
        
        // Update matching indicators in other active periods
        \App\Models\IndikatorKinerja::where('pegawai_id', $record->pegawai_id)
            ->whereIn('periode_id', $activePeriodIds)
            ->where('nama_indikator', $originalName)
            ->where('id', '!=', $record->id)
            ->update([
                'nama_indikator' => $data['nama_indikator'],
                'satuan' => $data['satuan'],
                'target_bulanan' => $data['target_bulanan'],
            ]);
            
        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
