<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    public function index()
    {
        return view('layout.app');
    }

    // Dashboard Kasir
    public function kasirDashboard()
    {
        $data = $this->getDashboardData();
        return view('kasir.dashboard', $data);
    }

    // Dashboard Admin
    public function adminDashboard()
    {
        $data = $this->getDashboardData();
        return view('admin.dashboard', $data);
    }

    // Dashboard Pemilik
    public function pemilikDashboard()
    {
        $data = $this->getDashboardData();
        return view('pemilik.dashboard', $data);
    }

    // Fungsi Reusable untuk Ambil Data Dashboard
    private function getDashboardData()
    {
        $user = User::count();

        $pembelian = Pembelian::select(
            DB::raw("DATE_FORMAT(created_at, '%M %Y') as bulan"),
            DB::raw("SUM(total) as total_pembelian"),
            DB::raw("MIN(created_at) as first_date")
        )->groupBy('bulan')->orderBy('first_date')->get();

        $penjualan = Penjualan::select(
            DB::raw("DATE_FORMAT(created_at, '%M %Y') as bulan"),
            DB::raw("SUM(total_bayar) as total_penjualan"),
            DB::raw("MIN(created_at) as first_date")
        )->groupBy('bulan')->orderBy('first_date')->get();

        $labels = $pembelian->pluck('bulan')
            ->merge($penjualan->pluck('bulan'))
            ->unique()
            ->values()
            ->toArray();

        $dataPembelian = [];
        $dataPenjualan = [];

        foreach ($labels as $bulan) {
            $dataPembelian[] = optional($pembelian->firstWhere('bulan', $bulan))->total_pembelian ?? 0;
            $dataPenjualan[] = optional($penjualan->firstWhere('bulan', $bulan))->total_penjualan ?? 0;
        }

        return compact('labels', 'dataPembelian', 'dataPenjualan', 'user');
    }
}
