<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\AbsensiKerja;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AbsensiKerjaTest extends TestCase
{
    // use RefreshDatabase;

    /** @test */
    public function it_can_create_an_absensi_record()
    {
        $user = User::factory()->create();

        $absensi = AbsensiKerja::create([
            'user_id' => $user->id,
            'status_masuk' => 'masuk',
            'waktu_mulai_kerja' => now(),
            'waktu_akhir_kerja' => now()->addHours(8),
        ]);

        $this->assertDatabaseHas('tbl_absen_kerja', [
            'user_id' => $user->id,
            'status_masuk' => 'masuk',
        ]);
    }

    /** @test */
    public function it_can_read_an_absensi_record()
    {
        $absensi = AbsensiKerja::factory()->create();

        $found = AbsensiKerja::find($absensi->id);

        $this->assertNotNull($found);
        $this->assertEquals($absensi->id, $found->id);
    }

    /** @test */
    public function it_can_update_an_absensi_record()
    {
        $absensi = AbsensiKerja::factory()->create();

        $absensi->update([
            'status_masuk' => 'cuti',
        ]);

        $this->assertDatabaseHas('tbl_absen_kerja', [
            'id' => $absensi->id,
            'status_masuk' => 'cuti',
        ]);
    }

    /** @test */
    public function it_can_delete_an_absensi_record()
    {
        $absensi = AbsensiKerja::factory()->create();

        $absensi->delete();

        $this->assertDatabaseMissing('tbl_absen_kerja', [
            'id' => $absensi->id,
        ]);
    }
}
