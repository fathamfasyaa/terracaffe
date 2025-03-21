<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    /**
     * Ambil data untuk diekspor.
     */
    public function collection()
    {
        return User::all(); // Sesuaikan dengan data yang ingin diekspor
    }
}
