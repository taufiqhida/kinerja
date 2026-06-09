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
    public ?string $selectedMonth = null;

    public function getMonthsInPeriod(): array
    {
        if (!$this->periodeAktif) {
            return [];
        }

        $start = \Carbon\Carbon::parse($this->periodeAktif->tanggal_mulai);
        $end = \Carbon\Carbon::parse($this->periodeAktif->tanggal_selesai);

        $months = [];
        $current = $start->copy()->startOfMonth();

        while ($current->lessThanOrEqualTo($end)) {
            $months[] = [
                'value' => $current->format('Y-m'),
                'label' => $current->translatedFormat('F Y'),
            ];
            $current->addMonth();
        }

        return $months;
    }

    public function updatedSelectedMonth(): void
    {
        $this->calculateCapaian();
    }

    public function mount(): void
    {
        $this->pegawai = Pegawai::where('user_id', auth()->id())
            ->with(['jabatan', 'unitKerja', 'kepala'])
            ->first();
        $this->periodeAktif = PeriodePenilaian::getActive();

        if ($this->periodeAktif) {
            $months = $this->getMonthsInPeriod();
            $currentMonthStr = now()->format('Y-m');
            $hasCurrentMonth = collect($months)->contains('value', $currentMonthStr);
            if ($hasCurrentMonth) {
                $this->selectedMonth = $currentMonthStr;
            } else {
                $this->selectedMonth = !empty($months) ? $months[0]['value'] : null;
            }
        }

        $this->calculateCapaian();
    }

    public function calculateCapaian(): void
    {
        $this->rataCapaian = 0;
        $this->adaRealisasiKosong = false;
        $this->jumlahIndikatorBelumAda = 0;

        if ($this->pegawai && $this->periodeAktif) {
            $indikators = IndikatorKinerja::where('pegawai_id', $this->pegawai->id)
                ->where('periode_id', $this->periodeAktif->id)
                ->get();

            $this->jumlahIndikator = $indikators->count();

            if ($this->jumlahIndikator > 0) {
                $totalCapaian = 0;
                foreach ($indikators as $ind) {
                    $totalRealisasi = 0;
                    if ($this->selectedMonth) {
                        $startOfMonth = \Carbon\Carbon::parse($this->selectedMonth . '-01')->startOfMonth()->toDateString();
                        $endOfMonth = \Carbon\Carbon::parse($this->selectedMonth . '-01')->endOfMonth()->toDateString();
                        $totalRealisasi = $ind->realisasiKinerja()
                            ->whereBetween('tanggal_realisasi', [$startOfMonth, $endOfMonth])
                            ->sum('jumlah_realisasi');
                    } else {
                        $totalRealisasi = $ind->total_realisasi;
                    }

                    $target = $ind->target_bulanan;
                    $capaian = $target > 0 ? ($totalRealisasi / $target) * 100 : 0;
                    $totalCapaian += $capaian;

                    if ($totalRealisasi == 0) {
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
