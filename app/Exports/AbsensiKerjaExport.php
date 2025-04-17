<?php

namespace App\Exports;

use App\Models\AbsensiKerja;
use Maatwebsite\Excel\Concerns\FromCollection;

class AbsensiKerjaExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // app/Exports/AbsensiKerjaExport.php
    public function collection()
    {
        return AbsensiKerja::all()->map(function ($item) {
            return [
                'Nama Karyawan' => $item->user->name,
                'Status' => ucfirst($item->status_masuk),
                'Waktu Mulai' => $item->waktu_mulai_kerja,
                'Waktu Selesai' => $item->waktu_akhir_kerja ?? '-',
            ];
        });
    }
}
