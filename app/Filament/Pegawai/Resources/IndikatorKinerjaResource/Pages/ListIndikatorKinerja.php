<?php

namespace App\Filament\Pegawai\Resources\IndikatorKinerjaResource\Pages;

use App\Filament\Pegawai\Resources\IndikatorKinerjaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIndikatorKinerja extends ListRecords
{
    protected static string $resource = IndikatorKinerjaResource::class;
    protected static ?string $title = 'Indikator Kinerja Saya';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Indikator'),
        ];
    }
}
