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
    public int $selectedPeriodeId = 0;
    public array $periodeOptions = [];

    public string $minTanggalRealisasi = '';
    public string $maxTanggalRealisasi = '';

    // Form state for adding realisasi (+ optional bukti dukung)
    public ?int $selectedIndikatorId = null;
    public int $jumlahRealisasi = 0;
    public string $keterangan = '';
    public string $tanggalRealisasi = '';
    public string $judulBukti = '';
    public string $linkBukti = '';

    // Form state for editing an indicator (before it is assessed)
    public ?int $editingIndikatorId = null;
    public string $editNamaIndikator = '';
    public string $editSatuan = '';
    public int $editTargetBulanan = 1;

    // Hasil penilaian data (merged from HasilPenilaian page)
    public array $nilaiAkhir = [];
    public array $penilaianPerilaku = [];
    public bool $sudahDinilai = false;

    protected function calculateDateBounds(): void
    {
        if ($this->periodeAktif) {
            $start = \Carbon\Carbon::parse($this->periodeAktif->tanggal_mulai);
            $end   = \Carbon\Carbon::parse($this->periodeAktif->tanggal_selesai);

            $this->minTanggalRealisasi = $start->toDateString();
            $this->maxTanggalRealisasi = $end->lessThan(now()) ? $end->toDateString() : now()->toDateString();

            $today = now();
            if ($today->between($start, $end)) {
                $this->tanggalRealisasi = $today->toDateString();
            } else {
                $this->tanggalRealisasi = $this->maxTanggalRealisasi;
            }
        } else {
            $this->minTanggalRealisasi = '';
            $this->maxTanggalRealisasi = '';
            $this->tanggalRealisasi    = '';
        }
    }

    public function updatedSelectedPeriodeId(): void
    {
        $this->periodeAktif = PeriodePenilaian::find($this->selectedPeriodeId);
        $this->calculateDateBounds();
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
        }

        $this->calculateDateBounds();
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
                'nama_perilaku'       => $pm->nama_perilaku,
                'urutan'              => $pm->urutan,
                'indikator'           => $pm->indikator->toArray(),
                'nilai'               => $penilaian?->nilai ?? null,
                'nilai_label'         => $penilaian ? PenilaianPerilaku::NILAI_LABEL[$penilaian->nilai] ?? '-' : '-',
                'nilai_emoji'         => $penilaian ? PenilaianPerilaku::NILAI_EMOJI[$penilaian->nilai] ?? '' : '',
                'ekspektasi_pimpinan' => $penilaian?->ekspektasi_pimpinan ?? '',
                'feedback'            => $penilaian?->feedback ?? '',
                'penilai'             => $penilaian?->kepala?->nama_lengkap ?? '-',
            ];
        }

        // Check if sudah dinilai
        $this->sudahDinilai = PenilaianPerilaku::where('pegawai_id', $this->pegawai->id)
            ->where('periode_id', $this->periodeAktif->id)
            ->exists();

        // Calculate nilai akhir
        $service         = new PerhitunganNilaiService();
        $this->nilaiAkhir = $service->hitungNilaiAkhir($this->pegawai, $this->periodeAktif);
    }

    // ── Tambah Realisasi ────────────────────────────────────────────────────────

    public function openTambahRealisasi(int $indikatorId): void
    {
        $this->editingIndikatorId = null; // tutup form edit jika terbuka
        $this->selectedIndikatorId = $indikatorId;
        $this->jumlahRealisasi     = 0;
        $this->keterangan          = '';
        $this->judulBukti          = '';
        $this->linkBukti           = '';

        $this->calculateDateBounds();
    }

    public function simpanRealisasi(): void
    {
        if (! $this->periodeAktif) {
            return;
        }

        $rules = [
            'selectedIndikatorId' => 'required|exists:indikator_kinerja,id',
            'jumlahRealisasi'     => 'required|integer|min:1',
            'tanggalRealisasi'    => "required|date|after_or_equal:{$this->minTanggalRealisasi}|before_or_equal:{$this->maxTanggalRealisasi}",
        ];

        if (! empty($this->judulBukti) || ! empty($this->linkBukti)) {
            $rules['judulBukti'] = 'required|string|max:255';
            $rules['linkBukti']  = 'required|url|max:500';
        }

        $this->validate($rules);

        RealisasiKinerjaModel::create([
            'indikator_kinerja_id' => $this->selectedIndikatorId,
            'jumlah_realisasi'     => $this->jumlahRealisasi,
            'keterangan'           => $this->keterangan,
            'tanggal_realisasi'    => $this->tanggalRealisasi,
        ]);

        if (! empty($this->judulBukti) && ! empty($this->linkBukti)) {
            BuktiDukungLink::create([
                'indikator_kinerja_id' => $this->selectedIndikatorId,
                'judul_bukti'          => $this->judulBukti,
                'link_bukti'           => $this->linkBukti,
            ]);
        }

        $this->selectedIndikatorId = null;
        $this->loadData();

        Notification::make()->title('Berhasil')->body('Realisasi berhasil ditambahkan.')->success()->send();
    }

    public function hapusRealisasi(int $id): void
    {
        RealisasiKinerjaModel::where('id', $id)->delete();
        $this->loadData();

        Notification::make()->title('Dihapus')->body('Realisasi berhasil dihapus.')->success()->send();
    }

    public function hapusBukti(int $id): void
    {
        BuktiDukungLink::where('id', $id)->delete();
        $this->loadData();

        Notification::make()->title('Dihapus')->body('Bukti dukung berhasil dihapus.')->success()->send();
    }

    // ── Edit & Hapus Indikator (hanya sebelum dinilai kepala) ──────────────────

    public function openEditIndikator(int $indikatorId): void
    {
        $indikator = IndikatorKinerja::find($indikatorId);
        if (! $indikator || $indikator->pegawai_id !== $this->pegawai?->id) {
            return;
        }

        if ($indikator->penilaianHasil()->exists()) {
            Notification::make()
                ->title('Tidak Dapat Diedit')
                ->body('Indikator ini sudah dinilai oleh atasan.')
                ->warning()
                ->send();
            return;
        }

        $this->selectedIndikatorId  = null; // tutup form tambah realisasi
        $this->editingIndikatorId   = $indikatorId;
        $this->editNamaIndikator    = $indikator->nama_indikator;
        $this->editSatuan           = $indikator->satuan;
        $this->editTargetBulanan    = $indikator->target_bulanan;
    }

    public function simpanEditIndikator(): void
    {
        $this->validate([
            'editNamaIndikator' => 'required|string|max:255',
            'editSatuan'        => 'required|string|max:100',
            'editTargetBulanan' => 'required|integer|min:1',
        ]);

        $indikator = IndikatorKinerja::find($this->editingIndikatorId);
        if (! $indikator || $indikator->pegawai_id !== $this->pegawai?->id) {
            return;
        }

        if ($indikator->penilaianHasil()->exists()) {
            Notification::make()->title('Ditolak')->body('Indikator sudah dinilai.')->warning()->send();
            return;
        }

        $indikator->update([
            'nama_indikator' => $this->editNamaIndikator,
            'satuan'         => $this->editSatuan,
            'target_bulanan' => $this->editTargetBulanan,
        ]);

        $this->editingIndikatorId = null;
        $this->loadData();

        Notification::make()->title('Tersimpan')->body('Indikator kinerja berhasil diperbarui.')->success()->send();
    }

    public function hapusIndikator(int $indikatorId): void
    {
        $indikator = IndikatorKinerja::find($indikatorId);
        if (! $indikator || $indikator->pegawai_id !== $this->pegawai?->id) {
            return;
        }

        if ($indikator->penilaianHasil()->exists()) {
            Notification::make()
                ->title('Tidak Dapat Dihapus')
                ->body('Indikator ini sudah dinilai oleh atasan.')
                ->warning()
                ->send();
            return;
        }

        // Hapus semua data terkait sebelum hapus indikator
        $indikator->realisasiKinerja()->delete();
        $indikator->buktiDukung()->delete();
        $indikator->feedbackHasil()->delete();
        $indikator->delete();

        if ($this->editingIndikatorId === $indikatorId) {
            $this->editingIndikatorId = null;
        }

        $this->loadData();

        Notification::make()->title('Dihapus')->body('Indikator kinerja berhasil dihapus.')->success()->send();
    }

    // ── Export PDF ─────────────────────────────────────────────────────────────

    public function exportPdf(): mixed
    {
        return $this->redirect(route('export.hasil-penilaian', [
            'periode_id' => $this->selectedPeriodeId,
        ]));
    }
}
