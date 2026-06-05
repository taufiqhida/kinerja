<?php

namespace App\Filament\Kepala\Pages;

use App\Models\Kepala;
use App\Models\PenilaianHasil;
use App\Models\PenilaianPerilaku;
use App\Models\PeriodePenilaian;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard Kepala';
    protected string $view = 'filament.kepala.pages.dashboard';
    protected static ?int $navigationSort = -2;

    public ?Kepala $kepala = null;
    public int $jumlahBawahan = 0;
    public int $jumlahDinilai = 0;
    public int $jumlahBelumDinilai = 0;
    public ?PeriodePenilaian $periodeAktif = null;

    public function mount(): void
    {
        $this->kepala = Kepala::where('user_id', auth()->id())->first();
        $this->periodeAktif = PeriodePenilaian::getActive();

        if ($this->kepala && $this->periodeAktif) {
            $this->jumlahBawahan = $this->kepala->pegawaiBawahan()->count();

            // Hitung pegawai yang sudah dinilai (punya penilaian perilaku lengkap)
            $bawahanIds = $this->kepala->pegawaiBawahan()->pluck('id');
            $this->jumlahDinilai = PenilaianPerilaku::where('kepala_id', $this->kepala->id)
                ->where('periode_id', $this->periodeAktif->id)
                ->whereIn('pegawai_id', $bawahanIds)
                ->distinct('pegawai_id')
                ->count('pegawai_id');
            $this->jumlahBelumDinilai = $this->jumlahBawahan - $this->jumlahDinilai;
        }
    }
}
