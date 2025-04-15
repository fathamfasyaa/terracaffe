<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class KategoriControllerTest extends TestCase
{
    // Jika kamu ingin database di-reset setiap test dijalankan, uncomment ini:
    // use RefreshDatabase;

    public function test_store_kategori(): void
    {
        // Ambil user yang sudah ada (pastikan user ini memang ada di database)
        $user = User::where('email', 'admin@gmail.com')->first();

        // Pastikan user ditemukan
        $this->assertNotNull($user, 'User admin@gmail.com tidak ditemukan di database');

        // Login sebagai user tersebut
        $this->actingAs($user);

        $data = [
            'nama_kategori' => 'sabuno"',
        ];

        // Kirim POST ke /admin/kategori
        $response = $this->post('/admin/kategori', $data);

        // Pastikan redirect berhasil
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.kategori'));

        // Cek data masuk ke database
        $this->assertDatabaseHas('kategoris', $data);
    }

    public function test_update_kategori(): void
    {
        // Ambil user yang sudah ada
        $user = User::where('email', 'admin@gmail.com')->first();

        // Pastikan user ditemukan
        $this->assertNotNull($user, 'User admin@gmail.com tidak ditemukan di database');

        // Login sebagai user tersebut
        $this->actingAs($user);

        // Buat kategori untuk diuji update-nya
        $kategori = Kategori::create([
            'nama_kategori' => 'sabunu',
        ]);

        // Data update
        $updateData = [
            'nama_kategori' => 'shampoj',
        ];

        // Kirim PUT request
        $response = $this->put("/admin/kategori/{$kategori->id}", $updateData);

        // Pastikan redirect berhasil
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.kategori'));

        // Pastikan data lama tidak ada dan data baru masuk
        $this->assertDatabaseMissing('kategoris', ['nama_kategori' => 'sabun']);
        $this->assertDatabaseHas('kategoris', ['nama_kategori' => 'shampoj']);
    }

    public function test_delete_kategori(): void
    {
        // Ambil user yang sudah ada
        $user = User::where('email', 'admin@gmail.com')->first();

        // Pastikan user ditemukan
        $this->assertNotNull($user, 'User admin@gmail.com tidak ditemukan di database');

        // Login sebagai user tersebut
        $this->actingAs($user);

        // Buat kategori untuk diuji hapus-nya
        $kategori = Kategori::create([
            'nama_kategori' => 'shampoj',
        ]);

        // Kirim DELETE request
        $response = $this->delete("/admin/kategori/{$kategori->id}");

        // Pastikan redirect berhasil
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.kategori'));

        // Pastikan data sudah dihapus dari database
        $this->assertDatabaseMissing('kategoris', ['nama_kategori' => 'sabun']);
    }
}
