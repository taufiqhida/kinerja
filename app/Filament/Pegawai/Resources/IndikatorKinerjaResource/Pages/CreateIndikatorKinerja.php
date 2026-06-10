<?php

namespace App\Filament\Pegawai\Resources\IndikatorKinerjaResource\Pages;

use App\Filament\Pegawai\Resources\IndikatorKinerjaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIndikatorKinerja extends CreateRecord
{
    protected static string $resource = IndikatorKinerjaResource::class;
    protected static ?string $title = 'Tambah Indikator Kinerja';

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $activePeriods = \App\Models\PeriodePenilaian::where('is_active', true)->get();
        
        if ($activePeriods->isEmpty()) {
            return parent::handleRecordCreation($data);
        }
        
        $firstRecord = null;
        foreach ($activePeriods as $periode) {
            $recordData = $data;
            $recordData['periode_id'] = $periode->id;
            
            $existingRecord = \App\Models\IndikatorKinerja::where('pegawai_id', $recordData['pegawai_id'])
                ->where('periode_id', $periode->id)
                ->where('nama_indikator', $recordData['nama_indikator'])
                ->first();
                
            if (!$existingRecord) {
                $created = \App\Models\IndikatorKinerja::create($recordData);
                if (!$firstRecord) {
                    $firstRecord = $created;
                }
            } else {
                if (!$firstRecord) {
                    $firstRecord = $existingRecord;
                }
            }
        }
        
        return $firstRecord ?? parent::handleRecordCreation($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
