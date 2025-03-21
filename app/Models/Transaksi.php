<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi'; // Pastikan sesuai dengan nama tabel di database
    protected $fillable = ['total', 'created_at']; // Sesuaikan dengan kolom di database
}
