<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('layout.app');
    }
    public function kasirDashboard(){
        $barang = Barang::count();
        $stokBarang = Barang::all();
        $kategori = Kategori::count();
        return view('kasir.dashboard', compact('barang', 'stokBarang', 'kategori'));
    }


public function adminDashboard()
{   
    //menghitung keseluruhan data
    $user = User::count();
    // Ambil data pembelian dan penjualan
    $pembelian = Pembelian::select(
    DB::raw("DATE_FORMAT(created_at, '%M %Y') as bulan"),
    DB::raw("SUM(total) as total_pembelian"),
    DB::raw("MIN(created_at) as first_date") 
)->groupBy('bulan')->orderBy('first_date')->get();



$penjualan = Penjualan::select(
    DB::raw("DATE_FORMAT(created_at, '%M %Y') as bulan"),
    DB::raw("SUM(total_bayar) as total_penjualan"),
    DB::raw("MIN(created_at) as first_date") // Tambahkan ini
)->groupBy('bulan')->orderBy('first_date')->get();

    // Ambil daftar bulan
    $labels = $pembelian->pluck('bulan')->merge($penjualan->pluck('bulan'))->unique()->values()->toArray();

    // Buat data pembelian dan penjualan per bulan
    $dataPembelian = [];
    $dataPenjualan = [];

    foreach ($labels as $bulan) {
        $dataPembelian[] = optional($pembelian->firstWhere('bulan', $bulan))->total_pembelian ?? 0;
        $dataPenjualan[] = optional($penjualan->firstWhere('bulan', $bulan))->total_penjualan ?? 0;
    }

    return view('admin.dashboard', compact('labels', 'dataPembelian', 'dataPenjualan', 'user'));
}

    public function pemilikDashboard(){
    //menghitung keseluruhan data
    $user = User::count();
    // Ambil data pembelian dan penjualan
    $pembelian = Pembelian::select(
    DB::raw("DATE_FORMAT(created_at, '%M %Y') as bulan"),
    DB::raw("SUM(total) as total_pembelian"),
    DB::raw("MIN(created_at) as first_date") 
)->groupBy('bulan')->orderBy('first_date')->get();

$penjualan = Penjualan::select(
    DB::raw("DATE_FORMAT(created_at, '%M %Y') as bulan"),
    DB::raw("SUM(total_bayar) as total_penjualan"),
    DB::raw("MIN(created_at) as first_date") // Tambahkan ini
)->groupBy('bulan')->orderBy('first_date')->get();

    // Ambil daftar bulan
    $labels = $pembelian->pluck('bulan')->merge($penjualan->pluck('bulan'))->unique()->values()->toArray();

    // Buat data pembelian dan penjualan per bulan
    $dataPembelian = [];
    $dataPenjualan = [];

    foreach ($labels as $bulan) {
        $dataPembelian[] = optional($pembelian->firstWhere('bulan', $bulan))->total_pembelian ?? 0;
        $dataPenjualan[] = optional($penjualan->firstWhere('bulan', $bulan))->total_penjualan ?? 0;
    }

    return view('pemilik.dashboard', compact('labels', 'dataPembelian', 'dataPenjualan', 'user'));
    }
}