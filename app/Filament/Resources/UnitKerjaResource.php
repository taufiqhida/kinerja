<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitKerjaResource\Pages;
use App\Models\UnitKerja;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class UnitKerjaResource extends Resource
{
    protected static ?string $model = UnitKerja::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Unit Kerja';
    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make()->schema([
                Forms\Components\TextInput::make('nama_unit')->label('Nama Unit Kerja')->required()->maxLength(255),
                Forms\Components\TextInput::make('kode_unit')->label('Kode Unit')->required()->unique(ignoreRecord: true)->maxLength(20),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_unit')->label('Kode')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nama_unit')->label('Nama Unit Kerja')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('pegawai_count')->label('Jml Pegawai')->counts('pegawai')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([Actions\EditAction::make(), Actions\DeleteAction::make()])
            ->bulkActions([Actions\BulkActionGroup::make([Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnitKerja::route('/'),
            'create' => Pages\CreateUnitKerja::route('/create'),
            'edit' => Pages\EditUnitKerja::route('/{record}/edit'),
        ];
    }
}
