<?php

namespace App\Filament\Kepala\Resources\DaftarPegawaiResource\Pages;

use App\Filament\Kepala\Resources\DaftarPegawaiResource;
use App\Models\FeedbackHasil;
use App\Models\IndikatorKinerja;
use App\Models\Kepala;
use App\Models\Pegawai;
use App\Models\PenilaianHasil;
use App\Models\PenilaianPerilaku;
use App\Models\PerilakuMaster;
use App\Models\PeriodePenilaian;
use App\Services\NotifikasiService;
use App\Services\PerhitunganNilaiService;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class DetailPegawai extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = DaftarPegawaiResource::class;
    protected string $view = 'filament.kepala.pages.detail-pegawai';
    protected static ?string $title = 'Detail & Penilaian Pegawai';

    public Pegawai $record;
    public ?Kepala $kepala = null;
    public ?PeriodePenilaian $periodeAktif = null;
    public array $indikators = [];
    public array $penilaianHasil = [];
    public array $feedbacks = [];
    public array $penilaianPerilaku = [];
    public array $nilaiAkhir = [];
    public string $overallPenilaianHasil = '';
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
        $this->loadData();
    }

    public function mount(Pegawai $record): void
    {
        $record->load(['jabatan', 'unitKerja', 'indikatorKinerja.realisasiKinerja', 'indikatorKinerja.buktiDukung']);
        $this->record = $record;
        $this->kepala = Kepala::where('user_id', auth()->id())->first();
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

        $this->loadData();
    }

    protected function loadData(): void
    {
        if (! $this->periodeAktif || ! $this->kepala) {
            return;
        }

        // Load indikator kinerja for this period
        $indikators = IndikatorKinerja::where('pegawai_id', $this->record->id)
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
                'feedbackHasil',
            ])
            ->get();

        $this->indikators = $indikators->toArray();

        // Load existing penilaian hasil
        $hasOverall = false;
        foreach ($indikators as $indikator) {
            $existing = PenilaianHasil::where('indikator_kinerja_id', $indikator->id)
                ->where('kepala_id', $this->kepala->id)
                ->first();
            $this->penilaianHasil[$indikator->id] = $existing?->nilai ?? '';
            if ($existing && !$hasOverall) {
                $this->overallPenilaianHasil = $existing->nilai;
                $hasOverall = true;
            }

            $existingFeedback = FeedbackHasil::where('indikator_kinerja_id', $indikator->id)
                ->where('kepala_id', $this->kepala->id)
                ->first();
            $this->feedbacks[$indikator->id] = $existingFeedback?->isi_feedback ?? '';
        }

        // Load existing penilaian perilaku
        $perilakuMasters = PerilakuMaster::where('is_active', true)->orderBy('urutan')->get();
        foreach ($perilakuMasters as $pm) {
            $existing = PenilaianPerilaku::where('pegawai_id', $this->record->id)
                ->where('kepala_id', $this->kepala->id)
                ->where('periode_id', $this->periodeAktif->id)
                ->where('perilaku_master_id', $pm->id)
                ->first();
            $this->penilaianPerilaku[$pm->id] = [
                'nilai' => $existing?->nilai ?? '',
                'ekspektasi_pimpinan' => $existing?->ekspektasi_pimpinan ?? '',
                'feedback' => $existing?->feedback ?? '',
            ];
        }

        // Calculate current scores
        $service = new PerhitunganNilaiService();
        $this->nilaiAkhir = $service->hitungNilaiAkhir($this->record, $this->periodeAktif);
    }

    public function getPerilakuMasters()
    {
        return PerilakuMaster::where('is_active', true)->with('indikator')->orderBy('urutan')->get();
    }

    public function simpanPenilaianHasil(): void
    {
        if (! $this->kepala || ! $this->periodeAktif) {
            Notification::make()->title('Error')->body('Tidak ada periode aktif atau data kepala.')->danger()->send();
            return;
        }

        if (empty($this->overallPenilaianHasil)) {
            Notification::make()
                ->title('Peringatan')
                ->body('Pilih Rating Hasil Kerja terlebih dahulu.')
                ->warning()
                ->send();
            return;
        }

        // Save penilaian hasil for all indicators
        foreach ($this->indikators as $indikator) {
            PenilaianHasil::updateOrCreate(
                [
                    'indikator_kinerja_id' => $indikator['id'],
                    'kepala_id' => $this->kepala->id,
                ],
                [
                    'nilai' => $this->overallPenilaianHasil,
                ]
            );
        }

        // Save feedbacks
        foreach ($this->feedbacks as $indikatorId => $isiFeedback) {
            if (empty($isiFeedback)) {
                continue;
            }

            FeedbackHasil::updateOrCreate(
                [
                    'indikator_kinerja_id' => $indikatorId,
                    'kepala_id' => $this->kepala->id,
                ],
                [
                    'isi_feedback' => $isiFeedback,
                ]
            );
        }

        $this->loadData();

        Notification::make()
            ->title('Berhasil')
            ->body('Penilaian hasil kerja berhasil disimpan.')
            ->success()
            ->send();
    }

    public function simpanPenilaianPerilaku(): void
    {
        if (! $this->kepala || ! $this->periodeAktif) {
            Notification::make()->title('Error')->body('Tidak ada periode aktif atau data kepala.')->danger()->send();
            return;
        }

        foreach ($this->penilaianPerilaku as $perilakuMasterId => $data) {
            if (empty($data['nilai'])) {
                continue;
            }

            PenilaianPerilaku::updateOrCreate(
                [
                    'pegawai_id' => $this->record->id,
                    'kepala_id' => $this->kepala->id,
                    'periode_id' => $this->periodeAktif->id,
                    'perilaku_master_id' => $perilakuMasterId,
                ],
                [
                    'ekspektasi_pimpinan' => $data['ekspektasi_pimpinan'] ?? '',
                    'feedback' => $data['feedback'] ?? '',
                    'nilai' => $data['nilai'],
                ]
            );
        }

        $this->loadData();

        // Check if all penilaian completed → notify pegawai
        $totalPerilaku = PerilakuMaster::where('is_active', true)->count();
        $dinilai = PenilaianPerilaku::where('pegawai_id', $this->record->id)
            ->where('kepala_id', $this->kepala->id)
            ->where('periode_id', $this->periodeAktif->id)
            ->count();

        if ($dinilai >= $totalPerilaku && $this->record->user) {
            $notifService = new NotifikasiService();
            $notifService->notifPenilaianSelesai($this->record->user);
        }

        Notification::make()
            ->title('Berhasil')
            ->body('Penilaian perilaku kerja berhasil disimpan.')
            ->success()
            ->send();
    }

    public function getTitle(): string
    {
        return 'Penilaian: ' . $this->record->nama_lengkap;
    }

    public function getBreadcrumbs(): array
    {
        return [
            DaftarPegawaiResource::getUrl() => 'Daftar Pegawai',
            '#' => $this->record->nama_lengkap,
        ];
    }
}
