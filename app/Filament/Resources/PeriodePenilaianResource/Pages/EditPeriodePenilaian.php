<?php
namespace App\Filament\Resources\PeriodePenilaianResource\Pages;
use App\Filament\Resources\PeriodePenilaianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditPeriodePenilaian extends EditRecord { protected static string $resource = PeriodePenilaianResource::class; protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; } }
