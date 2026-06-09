<?php

namespace App\Filament\Pegawai\Pages;

use App\Models\BuktiDukungLink;
use App\Models\IndikatorKinerja;
use App\Models\Pegawai;
use App\Models\PenilaianHasil;
use App\Models\PenilaianPerilaku;
use App\Models\PerilakuMaster;
use App\Models\PeriodePenilaian;
use App\Models\RealisasiKinerja as RealisasiKinerjaModel;
use App\Services\PerhitunganNilaiService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class RealisasiKinerja extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static ?string $navigationLabel = 'Realisasi & Hasil Kinerja';
    protected static string | \UnitEnum | null $navigationGroup = 'Kinerja';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'Realisasi & Hasil Kinerja';
    protected string $view = 'filament.pegawai.pages.realisasi-kinerja';

    public ?Pegawai $pegawai = null;
    public ?PeriodePenilaian $periodeAktif = null;
    public array $indikators = [];
    public ?string $selectedMonth = null;
    public int $selectedPeriodeId = 0;
    public array $periodeOptions = [];

    // Form state for adding realisasi (+ optional bukti dukung)
    public ?int $selectedIndikatorId = null;
    public int $jumlahRealisasi = 0;
    public string $keterangan = '';
    public string $tanggalRealisasi = '';
    public string $judulBukti = '';
    public string $linkBukti = '';

    // Hasil penilaian data (merged from HasilPenilaian page)
    public array $nilaiAkhir = [];
    public array $penilaianPerilaku = [];
    public bool $sudahDinilai = false;

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
        $currentMonthStr = now()->format('Y-m');
        if ($this->selectedMonth === $currentMonthStr) {
            $this->tanggalRealisasi = now()->format('Y-m-d');
        } else {
            $this->tanggalRealisasi = $this->selectedMonth ? $this->selectedMonth . '-01' : '';
        }
        $this->loadData();
    }

    public function updatedSelectedPeriodeId(): void
    {
        $this->periodeAktif = PeriodePenilaian::find($this->selectedPeriodeId);
        if ($this->periodeAktif) {
            $months = $this->getMonthsInPeriod();
            $this->selectedMonth = !empty($months) ? $months[0]['value'] : null;
        } else {
            $this->selectedMonth = null;
        }

        $currentMonthStr = now()->format('Y-m');
        if ($this->selectedMonth === $currentMonthStr) {
            $this->tanggalRealisasi = now()->format('Y-m-d');
        } else {
            $this->tanggalRealisasi = $this->selectedMonth ? $this->selectedMonth . '-01' : '';
        }

        $this->loadData();
    }

    public function mount(): void
    {
        $this->pegawai = Pegawai::where('user_id', auth()->id())->first();
        
        $this->periodeOptions = PeriodePenilaian::orderBy('tahun', 'desc')
            ->orderBy('tanggal_mulai', 'desc')
            ->pluck('nama_periode', 'id')
            ->toArray();

        $this->periodeAktif = PeriodePenilaian::getActive();
        
        if ($this->periodeAktif) {
            $this->selectedPeriodeId = $this->periodeAktif->id;
            $months = $this->getMonthsInPeriod();
            $currentMonthStr = now()->format('Y-m');
            $hasCurrentMonth = collect($months)->contains('value', $currentMonthStr);
            if ($hasCurrentMonth) {
                $this->selectedMonth = $currentMonthStr;
            } else {
                $this->selectedMonth = !empty($months) ? $months[0]['value'] : null;
            }
        }
        
        $currentMonthStr = now()->format('Y-m');
        if ($this->selectedMonth === $currentMonthStr) {
            $this->tanggalRealisasi = now()->format('Y-m-d');
        } else {
            $this->tanggalRealisasi = $this->selectedMonth ? $this->selectedMonth . '-01' : '';
        }
        
        $this->loadData();
    }

    public function loadData(): void
    {
        if (! $this->pegawai || ! $this->periodeAktif) {
            $this->indikators = [];
            return;
        }

        $this->indikators = IndikatorKinerja::where('pegawai_id', $this->pegawai->id)
            ->where('periode_id', $this->periodeAktif->id)
            ->with([
                'realisasiKinerja' => function ($q) {
                    if ($this->selectedMonth) {
                        $startOfMonth = \Carbon\Carbon::parse($this->selectedMonth . '-01')->startOfMonth()->toDateString();
                        $endOfMonth = \Carbon\Carbon::parse($this->selectedMonth . '-01')->endOfMonth()->toDateString();
                        $q->whereBetween('tanggal_realisasi', [$startOfMonth, $endOfMonth]);
                    }
                    $q->orderByDesc('tanggal_realisasi');
                },
                'buktiDukung',
                'penilaianHasil',
                'feedbackHasil.kepala',
            ])
            ->get()
            ->toArray();

        // Load penilaian perilaku
        $this->penilaianPerilaku = [];
        $perilakuMasters = PerilakuMaster::where('is_active', true)->orderBy('urutan')->with('indikator')->get();
        foreach ($perilakuMasters as $pm) {
            $penilaian = PenilaianPerilaku::where('pegawai_id', $this->pegawai->id)
                ->where('periode_id', $this->periodeAktif->id)
                ->where('perilaku_master_id', $pm->id)
                ->with('kepala')
                ->first();

            $this->penilaianPerilaku[] = [
                'nama_perilaku' => $pm->nama_perilaku,
                'urutan' => $pm->urutan,
                'indikator' => $pm->indikator->toArray(),
                'nilai' => $penilaian?->nilai ?? null,
                'nilai_label' => $penilaian ? PenilaianPerilaku::NILAI_LABEL[$penilaian->nilai] ?? '-' : '-',
                'nilai_emoji' => $penilaian ? PenilaianPerilaku::NILAI_EMOJI[$penilaian->nilai] ?? '' : '',
                'ekspektasi_pimpinan' => $penilaian?->ekspektasi_pimpinan ?? '',
                'feedback' => $penilaian?->feedback ?? '',
                'penilai' => $penilaian?->kepala?->nama_lengkap ?? '-',
            ];
        }

        // Check if sudah dinilai
        $this->sudahDinilai = PenilaianPerilaku::where('pegawai_id', $this->pegawai->id)
            ->where('periode_id', $this->periodeAktif->id)
            ->exists();

        // Calculate nilai akhir
        $service = new PerhitunganNilaiService();
        $this->nilaiAkhir = $service->hitungNilaiAkhir($this->pegawai, $this->periodeAktif);
    }

    public function openTambahRealisasi(int $indikatorId): void
    {
        $this->selectedIndikatorId = $indikatorId;
        $this->jumlahRealisasi = 0;
        $this->keterangan = '';
        
        $currentMonthStr = now()->format('Y-m');
        if ($this->selectedMonth === $currentMonthStr) {
            $this->tanggalRealisasi = now()->format('Y-m-d');
        } else {
            $this->tanggalRealisasi = $this->selectedMonth ? $this->selectedMonth . '-01' : '';
        }
        
        $this->judulBukti = '';
        $this->linkBukti = '';
    }

    public function simpanRealisasi(): void
    {
        $startOfMonth = \Carbon\Carbon::parse($this->selectedMonth . '-01')->startOfMonth()->toDateString();
        $endOfMonth = \Carbon\Carbon::parse($this->selectedMonth . '-01')->endOfMonth()->toDateString();

        $rules = [
            'selectedIndikatorId' => 'required|exists:indikator_kinerja,id',
            'jumlahRealisasi' => 'required|integer|min:1',
            'tanggalRealisasi' => "required|date|after_or_equal:{$startOfMonth}|before_or_equal:{$endOfMonth}",
        ];

        // Validate bukti fields only if at least one is filled
        if (! empty($this->judulBukti) || ! empty($this->linkBukti)) {
            $rules['judulBukti'] = 'required|string|max:255';
            $rules['linkBukti'] = 'required|url|max:500';
        }

        $this->validate($rules);

        RealisasiKinerjaModel::create([
            'indikator_kinerja_id' => $this->selectedIndikatorId,
            'jumlah_realisasi' => $this->jumlahRealisasi,
            'keterangan' => $this->keterangan,
            'tanggal_realisasi' => $this->tanggalRealisasi,
        ]);

        // Save bukti dukung if provided
        if (! empty($this->judulBukti) && ! empty($this->linkBukti)) {
            BuktiDukungLink::create([
                'indikator_kinerja_id' => $this->selectedIndikatorId,
                'judul_bukti' => $this->judulBukti,
                'link_bukti' => $this->linkBukti,
            ]);
        }

        $this->selectedIndikatorId = null;
        $this->loadData();

        Notification::make()
            ->title('Berhasil')
            ->body('Realisasi berhasil ditambahkan.')
            ->success()
            ->send();
    }



    public function hapusRealisasi(int $id): void
    {
        RealisasiKinerjaModel::where('id', $id)->delete();
        $this->loadData();

        Notification::make()
            ->title('Dihapus')
            ->body('Realisasi berhasil dihapus.')
            ->success()
            ->send();
    }

    public function hapusBukti(int $id): void
    {
        BuktiDukungLink::where('id', $id)->delete();
        $this->loadData();

        Notification::make()
            ->title('Dihapus')
            ->body('Bukti dukung berhasil dihapus.')
            ->success()
            ->send();
    }

    public function exportPdf(): mixed
    {
        return $this->redirect(route('export.hasil-penilaian', [
            'month' => $this->selectedMonth,
        ]));
    }
}
