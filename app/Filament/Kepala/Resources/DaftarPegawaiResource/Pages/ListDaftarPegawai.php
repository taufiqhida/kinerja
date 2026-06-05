<?php

namespace App\Filament\Kepala\Resources\DaftarPegawaiResource\Pages;

use App\Filament\Kepala\Resources\DaftarPegawaiResource;
use Filament\Resources\Pages\ListRecords;

class ListDaftarPegawai extends ListRecords
{
    protected static string $resource = DaftarPegawaiResource::class;
    protected static ?string $title = 'Daftar Pegawai Bawahan';
}
