<?php

namespace App\Filament\Pegawai\Pages;

use App\Models\IndikatorKinerja;
use App\Models\Pegawai;
use App\Models\PeriodePenilaian;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard Pegawai';
    protected string $view = 'filament.pegawai.pages.dashboard';
    protected static ?int $navigationSort = -2;

    public ?Pegawai $pegawai = null;
    public ?PeriodePenilaian $periodeAktif = null;
    public int $jumlahIndikator = 0;
    public int $jumlahIndikatorBelumAda = 0;
    public float $rataCapaian = 0;
    public bool $adaRealisasiKosong = false;

    public function mount(): void
    {
        $this->pegawai = Pegawai::where('user_id', auth()->id())
            ->with(['jabatan', 'unitKerja', 'kepala'])
            ->first();
        $this->periodeAktif = PeriodePenilaian::getActive();

        if ($this->pegawai && $this->periodeAktif) {
            $indikators = IndikatorKinerja::where('pegawai_id', $this->pegawai->id)
                ->where('periode_id', $this->periodeAktif->id)
                ->with('realisasiKinerja')
                ->get();

            $this->jumlahIndikator = $indikators->count();

            if ($this->jumlahIndikator > 0) {
                $totalCapaian = 0;
                foreach ($indikators as $ind) {
                    $totalCapaian += $ind->capaian_persen;
                    if ($ind->total_realisasi == 0) {
                        $this->adaRealisasiKosong = true;
                    }
                }
                $this->rataCapaian = round($totalCapaian / $this->jumlahIndikator, 1);
            } else {
                $this->jumlahIndikatorBelumAda = 1; // flag
            }
        }
    }
}
