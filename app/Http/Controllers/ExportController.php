<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AbsensiKerja;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\PDF;
use App\Exports\UsersExport;
use App\Exports\AbsensiKerjaExport;

class ExportController extends Controller
{
    /**
     * Export Users ke Excel
     */
    public function exportUsersExcel()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    /**
     * Export Users ke PDF
     */
    public function exportUsersPDF()
    {
        $users = User::all();
        $pdf = PDF::loadView('exports.users_pdf', compact('users'));
        return $pdf->download('users.pdf');
    }

    /**
     * Export Absensi Kerja ke Excel
     */
    public function exportAbsensiExcel()
    {
        return Excel::download(new AbsensiKerjaExport, 'absensi_kerja.xlsx');
    }

    /**
     * Export Absensi Kerja ke PDF
     */
    public function exportAbsensiPDF()
    {
        $data = AbsensiKerja::with('user')->get();
        $pdf = PDF::loadView('exports.absensi_pdf', compact('data'));
        return $pdf->download('laporan-absensi.pdf');
    }
}
