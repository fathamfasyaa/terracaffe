<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PelangganSeeder extends Seeder
{
    public function run()
    {
        // // Menghapus data lama
        // DB::table('pelanggan')->delete();

        // Menambahkan data baru
        DB::table('pelanggan')->insert([
            [
                'kode_pelanggan' => 'PLG001',
                'nama' => 'Andi Wijaya',
                'alamat' => 'Jl. Merdeka No.10, Jakarta',
                'no_telp' => '081234567890',
                'email' => 'andi@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_pelanggan' => 'PLG002',
                'nama' => 'Siti Rahma',
                'alamat' => 'Jl. Sudirman No.15, Bandung',
                'no_telp' => '081298765432',
                'email' => 'siti@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_pelanggan' => 'PLG003',
                'nama' => 'Budi Santoso',
                'alamat' => 'Jl. Diponegoro No.20, Surabaya',
                'no_telp' => '081276543210',
                'email' => 'budi@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
