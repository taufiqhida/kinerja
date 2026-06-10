<?php
namespace App\Filament\Resources;

use App\Filament\Resources\PeriodePenilaianResource\Pages;
use App\Models\PeriodePenilaian;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class PeriodePenilaianResource extends Resource
{
    protected static ?string $model = PeriodePenilaian::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Periode Penilaian';
    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make()->schema([
                Forms\Components\TextInput::make('nama_periode')->label('Nama Periode')->required()->placeholder('e.g. Semester 1 - 2026'),
                Forms\Components\TextInput::make('tahun')->label('Tahun')->required()->numeric()->minValue(2020)->maxValue(2099),
                Forms\Components\DatePicker::make('tanggal_mulai')->label('Tanggal Mulai')->required()->native(false),
                Forms\Components\DatePicker::make('tanggal_selesai')->label('Tanggal Selesai')->required()->native(false)->afterOrEqual('tanggal_mulai'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Periode Aktif')
                    ->helperText('Tentukan apakah periode ini aktif atau tidak.')
                    ->dehydrated(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_periode')->label('Periode')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('tahun')->label('Tahun')->sortable(),
                Tables\Columns\TextColumn::make('tanggal_mulai')->label('Mulai')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('tanggal_selesai')->label('Selesai')->date('d M Y')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('indikatorKinerja_count')
                    ->label('Jml. Indikator')
                    ->counts('indikatorKinerja')
                    ->alignCenter()
                    ->badge()
                    ->color('info'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\Action::make('aktifkanSalinIndikator')
                    ->label('Aktifkan & Salin Indikator')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading(fn (PeriodePenilaian $record) => 'Aktifkan ' . $record->nama_periode . '?')
                    ->modalDescription('Periode ini akan diaktifkan. Semua indikator kinerja dari periode aktif saat ini akan otomatis disalin ke periode baru (realisasi mulai dari 0). Periode aktif sebelumnya akan dinonaktifkan.')
                    ->modalSubmitActionLabel('Ya, Aktifkan & Salin')
                    ->visible(fn (PeriodePenilaian $record) => ! $record->is_active)
                    ->action(function (PeriodePenilaian $record) {
                        // Observer akan menangani salin indikator dan nonaktifkan periode lain
                        $record->update(['is_active' => true]);

                        \Filament\Notifications\Notification::make()
                            ->title('Periode Diaktifkan')
                            ->body("Periode \"{$record->nama_periode}\" kini aktif. Indikator kinerja telah disalin otomatis.")
                            ->success()
                            ->send();
                    }),
                Actions\Action::make('nonaktifkan')
                    ->label('Nonaktifkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (PeriodePenilaian $record) => $record->is_active)
                    ->action(fn (PeriodePenilaian $record) => $record->update(['is_active' => false])),
            ])
            ->bulkActions([Actions\BulkActionGroup::make([Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeriodePenilaian::route('/'),
            'create' => Pages\CreatePeriodePenilaian::route('/create'),
            'edit' => Pages\EditPeriodePenilaian::route('/{record}/edit'),
        ];
    }
}
