<?php

namespace App\Filament\Pegawai\Resources\IndikatorKinerjaResource\Pages;

use App\Filament\Pegawai\Resources\IndikatorKinerjaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIndikatorKinerja extends CreateRecord
{
    protected static string $resource = IndikatorKinerjaResource::class;
    protected static ?string $title = 'Tambah Indikator Kinerja';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
