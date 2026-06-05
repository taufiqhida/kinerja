<?php

namespace App\Filament\Kepala\Resources;

use App\Filament\Kepala\Resources\DaftarPegawaiResource\Pages;
use App\Models\Kepala;
use App\Models\Pegawai;
use App\Models\PenilaianPerilaku;
use App\Models\PeriodePenilaian;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DaftarPegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Daftar Pegawai';
    protected static string | \UnitEnum | null $navigationGroup = 'Penilaian';
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'daftar-pegawai';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('jabatan.nama_jabatan')
                    ->label('Jabatan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unitKerja.nama_unit')
                    ->label('Unit Kerja')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pangkat_golongan')
                    ->label('Pangkat/Gol')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status_penilaian')
                    ->label('Status Penilaian')
                    ->badge()
                    ->getStateUsing(function (Pegawai $record): string {
                        $periode = PeriodePenilaian::getActive();
                        if (! $periode) {
                            return 'Tidak Ada Periode';
                        }
                        $kepala = Kepala::where('user_id', auth()->id())->first();
                        if (! $kepala) {
                            return '-';
                        }
                        $count = PenilaianPerilaku::where('pegawai_id', $record->id)
                            ->where('kepala_id', $kepala->id)
                            ->where('periode_id', $periode->id)
                            ->count();
                        return $count >= 7 ? 'Selesai' : 'Belum Dinilai';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Selesai' => 'success',
                        'Belum Dinilai' => 'warning',
                        'Tidak Ada Periode' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Actions\Action::make('detail')
                    ->label('Lihat & Nilai')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('primary')
                    ->url(fn (Pegawai $record): string => static::getUrl('detail', ['record' => $record])),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $kepala = Kepala::where('user_id', auth()->id())->first();

        if (! $kepala) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->where('kepala_id', $kepala->id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDaftarPegawai::route('/'),
            'detail' => Pages\DetailPegawai::route('/{record}/detail'),
        ];
    }
}
