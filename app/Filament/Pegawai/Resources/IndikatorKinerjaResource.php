<?php

namespace App\Filament\Pegawai\Resources;

use App\Filament\Pegawai\Resources\IndikatorKinerjaResource\Pages;
use App\Models\IndikatorKinerja;
use App\Models\Pegawai;
use App\Models\PeriodePenilaian;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class IndikatorKinerjaResource extends Resource
{
    protected static ?string $model = IndikatorKinerja::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Indikator Kinerja';
    protected static string | \UnitEnum | null $navigationGroup = 'Kinerja';
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'indikator-kinerja';

    public static function form(Schema $schema): Schema
    {
        $periodeAktif = PeriodePenilaian::getActive();

        // Semua periode (untuk pilihan) — prioritaskan periode aktif
        $periodeOptions = PeriodePenilaian::orderBy('tahun', 'desc')
            ->orderBy('tanggal_mulai', 'desc')
            ->pluck('nama_periode', 'id')
            ->toArray();

        return $schema->schema([
            Section::make('Data Indikator Kinerja')->schema([
                Forms\Components\Hidden::make('pegawai_id')
                    ->default(fn () => Pegawai::where('user_id', auth()->id())->first()?->id),

                Forms\Components\Select::make('periode_id')
                    ->label('Periode Penilaian (Bulan)')
                    ->options($periodeOptions)
                    ->default($periodeAktif?->id)
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->helperText('Pilih bulan/periode yang ingin ditambahkan indikatornya. Indikator tiap periode bersifat independen.'),

                Forms\Components\TextInput::make('nama_indikator')
                    ->label('Nama Indikator Kinerja')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. Jumlah Pengkajian Pasien')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('satuan')
                    ->label('Satuan')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('e.g. pasien, kegiatan, laporan'),

                Forms\Components\TextInput::make('target_bulanan')
                    ->label('Target Bulanan')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->placeholder('e.g. 10'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        $periodeAktif = PeriodePenilaian::getActive();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('periode.nama_periode')
                    ->label('Periode')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_indikator')
                    ->label('Indikator Kinerja')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),
                Tables\Columns\TextColumn::make('satuan')
                    ->label('Satuan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('target_bulanan')
                    ->label('Target/Bln')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('total_realisasi')
                    ->label('Realisasi')
                    ->getStateUsing(fn (IndikatorKinerja $record): int => $record->total_realisasi)
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('capaian_persen')
                    ->label('Capaian (%)')
                    ->getStateUsing(fn (IndikatorKinerja $record): string => $record->capaian_persen . '%')
                    ->alignCenter()
                    ->color(fn (IndikatorKinerja $record): string => match (true) {
                        $record->capaian_persen >= 100 => 'success',
                        $record->capaian_persen >= 50  => 'warning',
                        default                         => 'danger',
                    }),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->before(function (IndikatorKinerja $record) {
                        // Delete hanya record ini — tidak merusak periode lain
                        // (default behavior sudah benar, ini hanya sebagai konfirmasi)
                    }),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('periode_id')
                    ->label('📅 Pilih Periode / Bulan')
                    ->options(fn () => PeriodePenilaian::orderBy('tahun', 'desc')
                        ->orderBy('tanggal_mulai', 'desc')
                        ->pluck('nama_periode', 'id'))
                    ->default(fn () => PeriodePenilaian::getActive()?->id)
                    ->placeholder('Semua periode')
                    ->indicator('Periode'),
            ])
            ->emptyStateHeading('Belum ada indikator di periode ini')
            ->emptyStateDescription('Klik "Tambah Indikator" untuk menambahkan indikator kinerja untuk periode yang dipilih. Atau gunakan "Salin dari Periode Sebelumnya" jika sudah punya indikator di bulan sebelumnya.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }

    public static function getEloquentQuery(): Builder
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->first();

        if (! $pegawai) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->where('pegawai_id', $pegawai->id);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListIndikatorKinerja::route('/'),
            'create' => Pages\CreateIndikatorKinerja::route('/create'),
            'edit'   => Pages\EditIndikatorKinerja::route('/{record}/edit'),
        ];
    }
}
