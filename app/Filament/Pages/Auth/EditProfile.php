<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                
                TextInput::make('no_hp')
                    ->label('No. HP / Telepon')
                    ->maxLength(20)
                    ->nullable(),
                
                TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->maxLength(20)
                    ->unique(table: 'pegawai', column: 'nik', ignorable: fn () => $this->getUser()->pegawai)
                    ->visible(fn () => $this->getUser()->role === 'pegawai'),
                
                TextInput::make('nip')
                    ->label('NIP')
                    ->required()
                    ->maxLength(30)
                    ->unique(table: 'kepala', column: 'nip', ignorable: fn () => $this->getUser()->kepala)
                    ->visible(fn () => $this->getUser()->role === 'kepala'),

                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getCurrentPasswordFormComponent(),
            ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->getUser();
        if ($user->role === 'pegawai') {
            $data['nik'] = $user->pegawai?->nik;
        } elseif ($user->role === 'kepala') {
            $data['nip'] = $user->kepala?->nip;
        }
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $nik = null;
        $nip = null;

        if ($record->role === 'pegawai') {
            $nik = $data['nik'] ?? null;
            unset($data['nik']);
        } elseif ($record->role === 'kepala') {
            $nip = $data['nip'] ?? null;
            unset($data['nip']);
        }

        // Parent handles user update (name, email, no_hp, password)
        $record = parent::handleRecordUpdate($record, $data);

        if ($record->role === 'pegawai' && $record->pegawai) {
            $record->pegawai->update(['nik' => $nik]);
        } elseif ($record->role === 'kepala' && $record->kepala) {
            $record->kepala->update(['nip' => $nip]);
        }

        return $record;
    }
}
