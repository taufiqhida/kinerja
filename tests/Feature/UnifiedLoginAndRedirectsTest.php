<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kepala;
use App\Models\Pegawai;
use App\Models\PeriodePenilaian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnifiedLoginAndRedirectsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a non-existent URL returns a 404 page with the redirect countdown.
     */
    public function test_404_page_redirect_countdown(): void
    {
        $response = $this->get('/non-existent-route-999');

        $response->assertStatus(404);
        $response->assertSee('Halaman Tidak Ditemukan');
        $response->assertSee('Kembali ke Landing Page dalam');
        $response->assertSee('countdown');
    }

    /**
     * Test that all legacy/default login pages redirect to /admin/login.
     */
    public function test_login_page_redirects(): void
    {
        $response = $this->get('/login');
        $response->assertRedirect('/admin/login');

        $response2 = $this->get('/pegawai/login');
        $response2->assertRedirect('/admin/login');

        $response3 = $this->get('/kepala/login');
        $response3->assertRedirect('/admin/login');
    }

    /**
     * Test export controller authorization logic.
     */
    public function test_export_controller_authorization(): void
    {
        // Setup Active Period
        $periode = PeriodePenilaian::create([
            'nama_periode' => 'Juni 2026',
            'tahun' => 2026,
            'tanggal_mulai' => '2026-06-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => true,
        ]);

        // Create Users & Models
        // Pegawai A
        $userPegawaiA = User::create([
            'name' => 'Pegawai A',
            'email' => 'pegawai_a@simkin.test',
            'password' => bcrypt('password'),
            'role' => 'pegawai',
            'is_active' => true,
        ]);
        $pegawaiA = Pegawai::create([
            'user_id' => $userPegawaiA->id,
            'nik' => '1111111111111111',
            'nama_lengkap' => 'Pegawai A Lengkap',
        ]);

        // Pegawai B
        $userPegawaiB = User::create([
            'name' => 'Pegawai B',
            'email' => 'pegawai_b@simkin.test',
            'password' => bcrypt('password'),
            'role' => 'pegawai',
            'is_active' => true,
        ]);
        $pegawaiB = Pegawai::create([
            'user_id' => $userPegawaiB->id,
            'nik' => '2222222222222222',
            'nama_lengkap' => 'Pegawai B Lengkap',
        ]);

        // Kepala K1
        $userKepala1 = User::create([
            'name' => 'Kepala 1',
            'email' => 'kepala_1@simkin.test',
            'password' => bcrypt('password'),
            'role' => 'kepala',
            'is_active' => true,
        ]);
        $kepala1 = Kepala::create([
            'user_id' => $userKepala1->id,
            'nip' => '199001012026061001',
            'nama_lengkap' => 'Kepala 1 Lengkap',
        ]);

        // Kepala K2
        $userKepala2 = User::create([
            'name' => 'Kepala 2',
            'email' => 'kepala_2@simkin.test',
            'password' => bcrypt('password'),
            'role' => 'kepala',
            'is_active' => true,
        ]);
        $kepala2 = Kepala::create([
            'user_id' => $userKepala2->id,
            'nip' => '199001012026061002',
            'nama_lengkap' => 'Kepala 2 Lengkap',
        ]);

        // Set Relationships: Pegawai A belongs to Kepala 1, Pegawai B belongs to Kepala 2
        $pegawaiA->update(['kepala_id' => $kepala1->id]);
        $pegawaiB->update(['kepala_id' => $kepala2->id]);

        // Admin User
        $userAdmin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@simkin.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 1. Pegawai A attempts to export:
        // - Without parameters or with their own ID -> success
        // - With Pegawai B's ID -> still exports their own record (status 200, but content is Pegawai A)
        $this->actingAs($userPegawaiA);
        $response = $this->get(route('export.hasil-penilaian'));
        $response->assertStatus(200);

        $responseWithB = $this->get(route('export.hasil-penilaian', ['pegawaiId' => $pegawaiB->id]));
        $responseWithB->assertStatus(200);

        // 2. Kepala 1 attempts to export:
        // - Subordinate Pegawai A -> success
        // - Non-subordinate Pegawai B -> failure (404/not found because we query where kepala_id matches)
        $this->actingAs($userKepala1);
        $responseK1_A = $this->get(route('export.hasil-penilaian', ['pegawaiId' => $pegawaiA->id]));
        $responseK1_A->assertStatus(200);

        $responseK1_B = $this->get(route('export.hasil-penilaian', ['pegawaiId' => $pegawaiB->id]));
        $responseK1_B->assertStatus(404);

        // 3. Admin attempts to export:
        // - Any employee -> success
        $this->actingAs($userAdmin);
        $responseAdmin_A = $this->get(route('export.hasil-penilaian', ['pegawaiId' => $pegawaiA->id]));
        $responseAdmin_A->assertStatus(200);

        $responseAdmin_B = $this->get(route('export.hasil-penilaian', ['pegawaiId' => $pegawaiB->id]));
        $responseAdmin_B->assertStatus(200);
    }
}
