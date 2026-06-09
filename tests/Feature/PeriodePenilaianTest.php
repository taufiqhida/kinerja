<?php

namespace Tests\Feature;

use App\Models\PeriodePenilaian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeriodePenilaianTest extends TestCase
{
    use RefreshDatabase;

    public function test_period_can_be_created_active_or_inactive(): void
    {
        $periodeActive = PeriodePenilaian::create([
            'nama_periode' => 'Periode Aktif',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-03-31',
            'is_active' => true,
        ]);

        $periodeInactive = PeriodePenilaian::create([
            'nama_periode' => 'Periode Tidak Aktif',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-04-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => false,
        ]);

        $this->assertTrue($periodeActive->fresh()->is_active);
        $this->assertFalse($periodeInactive->fresh()->is_active);
    }

    public function test_multiple_periods_can_be_active_simultaneously(): void
    {
        $periode1 = PeriodePenilaian::create([
            'nama_periode' => 'Periode 1',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-03-31',
            'is_active' => true,
        ]);

        $periode2 = PeriodePenilaian::create([
            'nama_periode' => 'Periode 2',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-04-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => true,
        ]);

        $this->assertTrue($periode1->fresh()->is_active);
        $this->assertTrue($periode2->fresh()->is_active);
    }

    public function test_can_deactivate_active_periods(): void
    {
        $periode = PeriodePenilaian::create([
            'nama_periode' => 'Periode 1',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-03-31',
            'is_active' => true,
        ]);

        $this->assertTrue($periode->fresh()->is_active);

        $periode->update(['is_active' => false]);

        $this->assertFalse($periode->fresh()->is_active);
    }

    public function test_deleting_active_period_does_not_affect_others(): void
    {
        $periode1 = PeriodePenilaian::create([
            'nama_periode' => 'Periode 1',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-03-31',
            'is_active' => true,
        ]);

        $periode2 = PeriodePenilaian::create([
            'nama_periode' => 'Periode 2',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-04-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => false,
        ]);

        $this->assertTrue($periode1->fresh()->is_active);
        $this->assertFalse($periode2->fresh()->is_active);

        $periode1->delete();

        $this->assertFalse($periode2->fresh()->is_active);
    }
}
