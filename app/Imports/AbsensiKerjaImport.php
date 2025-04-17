<?php

namespace App\Imports;

namespace App\Imports;

use App\Models\AbsensiKerja;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;

class AbsensiKerjaImport implements ToModel
{
    public function model(array $row)
    {
        // Abaikan header
        if ($row[0] === 'Nama' || $row[0] === null) return null;

        // Cari user_id berdasarkan nama (asumsinya kolom 0 = nama karyawan)
        $user = User::where('name', $row[0])->first();

        if (!$user) return null;

        return new AbsensiKerja([
            'user_id' => $user->id,
            'status_masuk' => strtolower($row[1]), // ex: masuk/sakit/cuti
            'waktu_mulai_kerja' => Carbon::parse($row[2])
        ]);
    }
}
