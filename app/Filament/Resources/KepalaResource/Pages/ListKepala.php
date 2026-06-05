<?php
namespace App\Filament\Resources\KepalaResource\Pages;
use App\Filament\Resources\KepalaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListKepala extends ListRecords { protected static string $resource = KepalaResource::class; protected function getHeaderActions(): array { return [Actions\CreateAction::make()->label('Tambah Kepala')]; } }
