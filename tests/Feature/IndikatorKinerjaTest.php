<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\PeriodePenilaian;
use App\Models\IndikatorKinerja;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndikatorKinerjaTest extends TestCase
{
    use RefreshDatabase;

    private Pegawai $pegawai;
    private User $user;
    private PeriodePenilaian $periodeJan;
    private PeriodePenilaian $periodeFeb;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Pegawai Test',
            'email' => 'pegawai@simkin.test',
            'password' => bcrypt('password'),
            'role' => 'pegawai',
            'is_active' => true,
        ]);

        $this->pegawai = Pegawai::create([
            'user_id' => $this->user->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Pegawai Test Lengkap',
        ]);

        $this->periodeJan = PeriodePenilaian::create([
            'nama_periode' => 'Januari 2026',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-01-31',
            'is_active' => true,
        ]);

        $this->periodeFeb = PeriodePenilaian::create([
            'nama_periode' => 'Februari 2026',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-02-01',
            'tanggal_selesai' => '2026-02-28',
            'is_active' => true,
        ]);
    }

    /**
     * Test that adding an indicator for one period does not automatically create it in another period.
     */
    public function test_indicators_are_independent_per_period(): void
    {
        $indicator = IndikatorKinerja::create([
            'pegawai_id' => $this->pegawai->id,
            'periode_id' => $this->periodeJan->id,
            'nama_indikator' => 'Laporan Bulanan',
            'satuan' => 'laporan',
            'target_bulanan' => 5,
        ]);

        // Assert that the indicator exists for Januari
        $this->assertDatabaseHas('indikator_kinerja', [
            'id' => $indicator->id,
            'periode_id' => $this->periodeJan->id,
            'nama_indikator' => 'Laporan Bulanan',
        ]);

        // Assert that there are no indicators for Februari
        $this->assertEquals(0, IndikatorKinerja::where('periode_id', $this->periodeFeb->id)->count());
    }

    /**
     * Test that updating an indicator in one period does not affect indicators in other periods.
     */
    public function test_edit_indicator_does_not_affect_other_periods(): void
    {
        $indicatorJan = IndikatorKinerja::create([
            'pegawai_id' => $this->pegawai->id,
            'periode_id' => $this->periodeJan->id,
            'nama_indikator' => 'Laporan Bulanan',
            'satuan' => 'laporan',
            'target_bulanan' => 5,
        ]);

        $indicatorFeb = IndikatorKinerja::create([
            'pegawai_id' => $this->pegawai->id,
            'periode_id' => $this->periodeFeb->id,
            'nama_indikator' => 'Laporan Bulanan',
            'satuan' => 'laporan',
            'target_bulanan' => 5,
        ]);

        // Edit the indicator in Januari
        $indicatorJan->update([
            'nama_indikator' => 'Laporan Bulanan Edited',
            'target_bulanan' => 10,
        ]);

        // Assert that Januari indicator is updated
        $this->assertEquals('Laporan Bulanan Edited', $indicatorJan->fresh()->nama_indikator);
        $this->assertEquals(10, $indicatorJan->fresh()->target_bulanan);

        // Assert that Februari indicator is NOT changed
        $this->assertEquals('Laporan Bulanan', $indicatorFeb->fresh()->nama_indikator);
        $this->assertEquals(5, $indicatorFeb->fresh()->target_bulanan);
    }

    /**
     * Test that deleting an indicator from one period does not delete/affect indicators in other periods.
     */
    public function test_delete_indicator_does_not_affect_other_periods(): void
    {
        $indicatorJan = IndikatorKinerja::create([
            'pegawai_id' => $this->pegawai->id,
            'periode_id' => $this->periodeJan->id,
            'nama_indikator' => 'Laporan Bulanan',
            'satuan' => 'laporan',
            'target_bulanan' => 5,
        ]);

        $indicatorFeb = IndikatorKinerja::create([
            'pegawai_id' => $this->pegawai->id,
            'periode_id' => $this->periodeFeb->id,
            'nama_indikator' => 'Laporan Bulanan',
            'satuan' => 'laporan',
            'target_bulanan' => 5,
        ]);

        // Delete Januari indicator
        $indicatorJan->delete();

        // Assert that Januari indicator is gone
        $this->assertDatabaseMissing('indikator_kinerja', [
            'id' => $indicatorJan->id,
        ]);

        // Assert that Februari indicator still exists
        $this->assertDatabaseHas('indikator_kinerja', [
            'id' => $indicatorFeb->id,
            'periode_id' => $this->periodeFeb->id,
        ]);
    }
}
