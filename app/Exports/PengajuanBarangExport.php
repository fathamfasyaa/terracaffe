<?php

namespace App\Exports;

use App\Models\PengajuanBarang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengajuanBarangExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return PengajuanBarang::select('id', 'pengaju', 'nama_barang', 'deskripsi', 'tgl_pengajuan', 'qty', 'status')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Pengaju', 'Nama Barang', 'Deskripsi', 'Tgl Pengajuan', 'Qty', 'Status'];
    }
}
