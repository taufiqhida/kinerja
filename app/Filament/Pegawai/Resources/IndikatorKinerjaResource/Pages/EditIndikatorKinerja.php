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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
