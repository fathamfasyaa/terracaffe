<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pembelian;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role == 'pemilik') {
            return view('Pemilik/laporan/index');
        } elseif ($user->role == 'admin') {
            return view('Admin/laporan/index');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function laporanPenjualan(Request $request)
    {
        $query = Penjualan::with('detailPenjualan.barang')->latest();

        if ($request->filled('filter_tanggal')) {
            $query->whereDate('tgl_faktur', $request->filter_tanggal);
        }

        if ($request->filled('filter_bulan')) {
            $bulan = date('m', strtotime($request->filter_bulan));
            $tahun = date('Y', strtotime($request->filter_bulan));
            $query->whereMonth('tgl_faktur', $bulan)->whereYear('tgl_faktur', $tahun);
        }

        $penjualan = $query->get();
        $user = auth()->user();

        if ($user->role == 'pemilik') {
            return view('Pemilik.laporan.penjualan.index', compact('penjualan'));
        } elseif ($user->role == 'admin') {
            return view('Admin.laporan.penjualan.index', compact('penjualan'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function laporanPembelian()
    {
        $pembelian = Pembelian::latest()->paginate(10);
        $user = auth()->user();

        if ($user->role == 'pemilik') {
            return view('pemilik.laporan.pembelian.index', compact('pembelian'));
        } elseif ($user->role == 'admin') {
            return view('admin.laporan.pembelian.index', compact('pembelian'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function exportPdf(Request $request)
    {
        $query = Penjualan::with('detailPenjualan.barang');

        // Tambahkan filter jika diperlukan
        if ($request->filter_tanggal) {
            $query->whereDate('tgl_faktur', $request->filter_tanggal);
        }

        if ($request->filter_bulan) {
            $query->whereMonth('tgl_faktur', substr($request->filter_bulan, 5, 2))
                ->whereYear('tgl_faktur', substr($request->filter_bulan, 0, 4));
        }

        $penjualan = $query->get();

        $pdf = Pdf::loadView('Admin.laporan.penjualan.pdf', compact('penjualan'));
        return $pdf->download('laporan-penjualan.pdf');
    }
}
