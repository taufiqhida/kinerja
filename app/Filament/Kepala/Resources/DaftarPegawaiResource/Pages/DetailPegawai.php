<?php

namespace App\Filament\Kepala\Resources\DaftarPegawaiResource\Pages;

use App\Filament\Kepala\Resources\DaftarPegawaiResource;
use App\Models\IndikatorKinerja;
use App\Models\Kepala;
use App\Models\Pegawai;
use App\Models\PenilaianHasil;
use App\Models\PenilaianPerilaku;
use App\Models\PerilakuMaster;
use App\Models\PeriodePenilaian;
use App\Models\TtdPenilaian;
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
    public string $overallPenilaianHasil = '';
    public array $penilaianPerilaku = [];
    public array $nilaiAkhir = [];
    public int $selectedPeriodeId = 0;
    public array $periodeOptions = [];
    public ?string $pdfUrl = null;

    public function updatedSelectedPeriodeId(): void
    {
        $this->periodeAktif = PeriodePenilaian::find($this->selectedPeriodeId);
        $this->loadData();
    }

    public function mount(Pegawai $record): void
    {
        $record->load(['jabatan', 'unitKerja', 'indikatorKinerja.realisasiKinerja', 'indikatorKinerja.buktiDukung']);
        $this->record = $record;
        $this->kepala = Kepala::where('user_id', auth()->id())->first();
        
        $this->periodeOptions = PeriodePenilaian::orderBy('tahun', 'desc')
            ->orderBy('tanggal_mulai', 'desc')
            ->pluck('nama_periode', 'id')
            ->toArray();

        $this->periodeAktif = PeriodePenilaian::getActive();

        if ($this->periodeAktif) {
            $this->selectedPeriodeId = $this->periodeAktif->id;
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
                    $q->orderByDesc('tanggal_realisasi');
                },
                'buktiDukung',
                'penilaianHasil',
                'feedbackHasil',
            ])
            ->get();

        $this->indikators = $indikators->toArray();

        // Load existing penilaian hasil per-indikator
        $hasOverall = false;
        foreach ($indikators as $indikator) {
            $existing = PenilaianHasil::where('indikator_kinerja_id', $indikator->id)
                ->where('kepala_id', $this->kepala->id)
                ->first();
            $this->penilaianHasil[$indikator->id] = [
                'nilai'               => $existing?->nilai ?? '',
                'ekspektasi_pimpinan' => $existing?->ekspektasi_pimpinan ?? '',
                'feedback'            => $existing?->feedback ?? '',
            ];
            // Ambil overall dari indikator pertama yang sudah dinilai
            if ($existing && ! $hasOverall) {
                $this->overallPenilaianHasil = $existing->nilai;
                $hasOverall = true;
            }
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

        // Pastikan minimal satu indikator sudah dinilai
        $adaNilai = collect($this->penilaianHasil)->filter(fn($d) => ! empty($d['nilai']))->isNotEmpty();
        if (! $adaNilai && empty($this->overallPenilaianHasil)) {
            Notification::make()
                ->title('Peringatan')
                ->body('Pilih nilai untuk minimal satu indikator kinerja.')
                ->warning()
                ->send();
            return;
        }

        $this->doSimpanHasil();

        $this->loadData();

        Notification::make()
            ->title('Berhasil')
            ->body('Penilaian hasil kerja berhasil disimpan.')
            ->success()
            ->send();
    }

    /**
     * Menyimpan data penilaian hasil kerja (digunakan oleh simpanPenilaianHasil dan simpanSemua).
     */
    protected function doSimpanHasil(): void
    {
        // Jika ada overall rating, terapkan ke semua indikator yang belum punya nilai
        foreach ($this->penilaianHasil as $indikatorId => $data) {
            $nilai = $data['nilai'] ?: $this->overallPenilaianHasil;
            if (empty($nilai)) {
                continue;
            }

            PenilaianHasil::updateOrCreate(
                [
                    'indikator_kinerja_id' => $indikatorId,
                    'kepala_id'            => $this->kepala->id,
                ],
                [
                    'nilai'               => $nilai,
                    'ekspektasi_pimpinan' => $data['ekspektasi_pimpinan'] ?? '',
                    'feedback'            => $data['feedback'] ?? '',
                ]
            );
        }
    }

    public function simpanPenilaianPerilaku(): void
    {
        if (! $this->kepala || ! $this->periodeAktif) {
            Notification::make()->title('Error')->body('Tidak ada periode aktif atau data kepala.')->danger()->send();
            return;
        }

        $this->doSimpanPerilaku();

        $this->loadData();

        Notification::make()
            ->title('Berhasil')
            ->body('Penilaian perilaku kerja berhasil disimpan.')
            ->success()
            ->send();
    }

    /**
     * Menyimpan data penilaian perilaku (digunakan oleh simpanPenilaianPerilaku dan simpanSemua).
     */
    protected function doSimpanPerilaku(): void
    {
        foreach ($this->penilaianPerilaku as $perilakuMasterId => $data) {
            if (empty($data['nilai'])) {
                continue;
            }

            PenilaianPerilaku::updateOrCreate(
                [
                    'pegawai_id'         => $this->record->id,
                    'kepala_id'          => $this->kepala->id,
                    'periode_id'         => $this->periodeAktif->id,
                    'perilaku_master_id' => $perilakuMasterId,
                ],
                [
                    'ekspektasi_pimpinan' => $data['ekspektasi_pimpinan'] ?? '',
                    'feedback'            => $data['feedback'] ?? '',
                    'nilai'               => $data['nilai'],
                ]
            );
        }

        // Kirim notifikasi ke pegawai jika semua perilaku sudah dinilai
        $totalPerilaku = PerilakuMaster::where('is_active', true)->count();
        $dinilai = PenilaianPerilaku::where('pegawai_id', $this->record->id)
            ->where('kepala_id', $this->kepala->id)
            ->where('periode_id', $this->periodeAktif->id)
            ->count();

        if ($dinilai >= $totalPerilaku && $this->record->user) {
            $notifService = new NotifikasiService();
            $notifService->notifPenilaianSelesai($this->record->user);
        }
    }

    /**
     * Simpan semua penilaian (Hasil Kerja + Perilaku) sekaligus dalam satu aksi.
     * Setelah simpan, generate QR code TTD dan arahkan ke PDF.
     */
    public function simpanSemua(): void
    {
        if (! $this->kepala || ! $this->periodeAktif) {
            Notification::make()->title('Error')->body('Tidak ada periode aktif atau data kepala.')->danger()->send();
            return;
        }

        // Validasi: minimal ada overall rating atau satu indikator dinilai
        $adaNilaiHasil = collect($this->penilaianHasil)->filter(fn($d) => ! empty($d['nilai']))->isNotEmpty()
            || ! empty($this->overallPenilaianHasil);

        if (! $adaNilaiHasil) {
            Notification::make()
                ->title('Peringatan')
                ->body('Pilih nilai untuk minimal satu indikator kinerja atau pilih Rating Keseluruhan.')
                ->warning()
                ->send();
            return;
        }

        $this->doSimpanHasil();
        $this->doSimpanPerilaku();

        // Generate / refresh token TTD untuk QR code
        $ttd = TtdPenilaian::generateOrRefresh(
            pegawaiId: $this->record->id,
            kepalaId:  $this->kepala->id,
            periodeId: $this->periodeAktif->id,
        );

        $this->loadData();

        // Set URL PDF agar JS bisa buka tab baru
        $this->pdfUrl = route('export.hasil-penilaian', [
            'pegawaiId'  => $this->record->id,
            'periode_id' => $this->periodeAktif->id,
        ]);

        // Dispatch ke browser untuk buka PDF di tab baru
        $this->dispatch('open-pdf', url: $this->pdfUrl);

        Notification::make()
            ->title('✅ Semua Penilaian Tersimpan & TTD Dibuat')
            ->body('Penilaian berhasil disimpan. QR Code TTD sudah digenerate. PDF laporan sedang dibuka.')
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
