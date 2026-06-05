<?php
namespace App\Filament\Resources;

use App\Filament\Resources\KepalaResource\Pages;
use App\Models\Jabatan;
use App\Models\Kepala;
use App\Models\UnitKerja;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class KepalaResource extends Resource
{
    protected static ?string $model = Kepala::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Data Kepala / Penilai';
    protected static string | \UnitEnum | null $navigationGroup = 'Manajemen Akun';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Data Kepala / Penilai')->schema([
                Forms\Components\Select::make('user_id')->label('Akun Pengguna')
                    ->relationship('user', 'name', fn ($query) => $query->where('role', 'kepala'))
                    ->required()->searchable()->preload()->createOptionForm([
                        Forms\Components\TextInput::make('name')->label('Nama')->required(),
                        Forms\Components\TextInput::make('email')->email()->required()->unique(),
                        Forms\Components\TextInput::make('password')->password()->required(),
                        Forms\Components\Hidden::make('role')->default('kepala'),
                    ]),
                Forms\Components\TextInput::make('nip')->label('NIP')->required()->unique(ignoreRecord: true)->maxLength(30),
                Forms\Components\TextInput::make('nama_lengkap')->label('Nama Lengkap')->required()->maxLength(255),
                Forms\Components\TextInput::make('pangkat_golongan')->label('Pangkat/Golongan')->maxLength(100),
                Forms\Components\Select::make('jabatan_id')->label('Jabatan')
                    ->options(Jabatan::pluck('nama_jabatan', 'id'))->searchable()->preload(),
                Forms\Components\Select::make('unit_kerja_id')->label('Unit Kerja')
                    ->options(UnitKerja::pluck('nama_unit', 'id'))->searchable()->preload(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nip')->label('NIP')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nama_lengkap')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('pangkat_golongan')->label('Pangkat/Gol')->sortable(),
                Tables\Columns\TextColumn::make('jabatan.nama_jabatan')->label('Jabatan')->sortable(),
                Tables\Columns\TextColumn::make('unitKerja.nama_unit')->label('Unit Kerja')->sortable(),
                Tables\Columns\TextColumn::make('pegawai_bawahan_count')->label('Jml Bawahan')->counts('pegawaiBawahan')->sortable(),
            ])
            ->actions([Actions\EditAction::make(), Actions\DeleteAction::make()])
            ->bulkActions([Actions\BulkActionGroup::make([Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKepala::route('/'),
            'create' => Pages\CreateKepala::route('/create'),
            'edit' => Pages\EditKepala::route('/{record}/edit'),
        ];
    }
}
