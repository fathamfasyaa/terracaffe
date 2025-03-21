<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Sesuaikan dengan model yang akan diekspor
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\PDF;
use App\Exports\UsersExport;

class ExportController extends Controller
{
    /**
     * Export data ke Excel
     */
    public function exportExcel()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    /**
     * Export data ke PDF
     */
    public function exportPDF()
    {
        $users = User::all();
        $pdf = PDF::loadView('exports.users_pdf', compact('users'));
        return $pdf->download('users.pdf');
    }
}
