<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;
use App\Events\TransaksiUpdated;
use App\Models\Penjualan;

class TransaksiController extends Controller
{
    public function index()
    {
        $dataPembelian = [1000000, 1500000, 2000000, 1800000]; // Contoh data pembelian
        $dataPenjualan = [900000, 1400000, 1900000, 1700000];  // Contoh data penjualan
        $labels = ['Jan', 'Feb', 'Mar', 'Apr']; // Contoh label bulan
        $user = 120; // Jumlah pengguna aktif

        return view('admin.dashboard', compact('dataPembelian', 'dataPenjualan', 'labels', 'user'));
    }

    public function getData()
    {
        $data = Penjualan::selectRaw("DATE(tgl_faktur) as tanggal, COUNT(*) as jumlah_transaksi, SUM(total_bayar) as total_pendapatan")
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        return response()->json($data);
    }

    public function broadcastData()
    {
        $data = Penjualan::selectRaw("DATE(tgl_faktur) as tanggal, COUNT(*) as jumlah_transaksi, SUM(total_bayar) as total_pendapatan")
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        broadcast(new TransaksiUpdated($data))->toOthers();

        return response()->json(['message' => 'Data broadcasted successfully!']);
    }
}
