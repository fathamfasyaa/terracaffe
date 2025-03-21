<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanBarang extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_barangs';
    protected $fillable = ['pengaju', 'nama_barang', 'deskripsi', 'tgl_pengajuan', 'qty', 'status'];

    protected $casts = [
        'tgl_pengajuan' => 'date', // Konversi otomatis ke Carbon (DateTime)
    ];
}
