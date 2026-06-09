<?php

namespace Tests\Feature;

use App\Models\PeriodePenilaian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeriodePenilaianTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_period_is_automatically_active(): void
    {
        $periode = PeriodePenilaian::create([
            'nama_periode' => 'Periode 1',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-03-31',
            'is_active' => false, // Try to set it to false
        ]);

        $this->assertTrue($periode->fresh()->is_active);
    }

    public function test_activating_new_period_deactivates_others(): void
    {
        $periode1 = PeriodePenilaian::create([
            'nama_periode' => 'Periode 1',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-03-31',
            'is_active' => true,
        ]);

        $this->assertTrue($periode1->fresh()->is_active);

        $periode2 = PeriodePenilaian::create([
            'nama_periode' => 'Periode 2',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-04-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => true, // Activate the second one
        ]);

        $this->assertTrue($periode2->fresh()->is_active);
        $this->assertFalse($periode1->fresh()->is_active);
    }

    public function test_cannot_deactivate_the_only_active_period(): void
    {
        $periode = PeriodePenilaian::create([
            'nama_periode' => 'Periode 1',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-03-31',
            'is_active' => true,
        ]);

        $this->assertTrue($periode->fresh()->is_active);

        // Try to update is_active to false
        $periode->update(['is_active' => false]);

        $this->assertTrue($periode->fresh()->is_active);
    }

    public function test_deleting_active_period_activates_another(): void
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

        // Delete active period
        $periode1->delete();

        // The remaining period should be activated
        $this->assertTrue($periode2->fresh()->is_active);
    }
}
