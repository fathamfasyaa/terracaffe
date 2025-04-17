<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiKerja extends Model
{
    use HasFactory;

    protected $table = 'tbl_absen_kerja';

    protected $fillable = [
        'user_id',
        'status_masuk',
        'waktu_mulai_kerja',
        'waktu_akhir_kerja'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
