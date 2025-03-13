<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition()
    {
        return [
            'kode_barang' => $this->faker->unique()->numberBetween(1, 9999),
            'kategori_id' => Kategori::inRandomOrder()->first()->id ?? 1, // Gunakan kategori acak atau default 1
            'nama_barang' => $this->faker->word(),
            'satuan' => $this->faker->randomElement(['pcs', 'box', 'set']),
            'harga_jual' => $this->faker->numberBetween(1000, 100000),
            'stok' => $this->faker->numberBetween(1, 100),
            'user_id' => User::inRandomOrder()->first()->id ?? 1, // Gunakan user acak atau default 1
        ];
    }
}
