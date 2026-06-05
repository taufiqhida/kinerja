<?php

namespace App\Filament\Kepala\Pages;

use App\Models\Kepala;
use App\Models\PeriodePenilaian;
use App\Services\PerhitunganNilaiService;
use Filament\Pages\Page;

class RekapPenilaian extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationLabel = 'Rekap Penilaian';
    protected static string | \UnitEnum | null $navigationGroup = 'Penilaian';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'Rekap Penilaian';
    protected string $view = 'filament.kepala.pages.rekap-penilaian';

    public array $ranking = [];
    public ?PeriodePenilaian $periodeAktif = null;
    public ?Kepala $kepala = null;
    public int $selectedPeriodeId = 0;
    public array $periodeOptions = [];

    public function mount(): void
    {
        $this->kepala = Kepala::where('user_id', auth()->id())->first();
        $this->periodeAktif = PeriodePenilaian::getActive();
        $this->periodeOptions = PeriodePenilaian::orderBy('tahun', 'desc')
            ->orderBy('tanggal_mulai', 'desc')
            ->pluck('nama_periode', 'id')
            ->toArray();

        if ($this->periodeAktif) {
            $this->selectedPeriodeId = $this->periodeAktif->id;
            $this->loadRanking();
        }
    }

    public function updatedSelectedPeriodeId(): void
    {
        $this->loadRanking();
    }

    protected function loadRanking(): void
    {
        if (! $this->kepala || ! $this->selectedPeriodeId) {
            $this->ranking = [];
            return;
        }

        $periode = PeriodePenilaian::find($this->selectedPeriodeId);
        if (! $periode) {
            $this->ranking = [];
            return;
        }

        $service = new PerhitunganNilaiService();
        $this->ranking = $service->getRanking($periode, $this->kepala->id);
    }
}
