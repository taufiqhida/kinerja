<?php
namespace App\Filament\Resources;

use App\Filament\Resources\JabatanResource\Pages;
use App\Models\Jabatan;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class JabatanResource extends Resource
{
    protected static ?string $model = Jabatan::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Jabatan';
    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make()->schema([
                Forms\Components\TextInput::make('nama_jabatan')->label('Nama Jabatan')->required()->maxLength(255),
                Forms\Components\TextInput::make('kelas_jabatan')->label('Kelas Jabatan')->maxLength(10),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_jabatan')->label('Nama Jabatan')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('kelas_jabatan')->label('Kelas')->sortable(),
                Tables\Columns\TextColumn::make('pegawai_count')->label('Jml Pegawai')->counts('pegawai')->sortable(),
            ])
            ->actions([Actions\EditAction::make(), Actions\DeleteAction::make()])
            ->bulkActions([Actions\BulkActionGroup::make([Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJabatan::route('/'),
            'create' => Pages\CreateJabatan::route('/create'),
            'edit' => Pages\EditJabatan::route('/{record}/edit'),
        ];
    }
}
