<?php
namespace App\Filament\Resources\KepalaResource\Pages;
use App\Filament\Resources\KepalaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditKepala extends EditRecord { protected static string $resource = KepalaResource::class; protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; } }
