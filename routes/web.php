<?php

use App\Http\Controllers\AbsensiKerjaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengajuanBarangController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TransaksiController;


Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('dashboard', function () {
    if (Auth::check()) {
        $user = Auth::user();

        // Redirect berdasarkan role
        if ($user->role === 'pemilik') {
            return redirect()->route('pemilik.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'kasir') {
            return redirect()->route('kasir.dashboard');
        }

        // Jika role tidak dikenali, logout & arahkan ke login
        Auth::logout();
        return redirect()->route('login')->with('error', 'Role tidak valid.');
    }

    return redirect()->route('login');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');

    Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/admin/laporan/penjualan', [LaporanController::class, 'laporanPenjualan'])->name('admin.laporan.penjualan');
    Route::get('/admin/laporan/penjualan/pdf', [LaporanController::class, 'exportPdf'])->name('admin.laporan.penjualan.pdf');

    Route::get('/admin/laporan/pembelian', [LaporanController::class, 'laporanPembelian'])->name('admin.laporan.pembelian');

    Route::get('/admin/pemasok', [PemasokController::class, 'index'])->name('admin.pemasok.index');
    Route::get('/admin/pemasok/create', [PemasokController::class, 'create'])->name('admin.pemasok.create');
    Route::post('/admin/pemasok', [PemasokController::class, 'store'])->name('admin.pemasok.store');
    Route::get('/admin/pemasok/{pemasok}', [PemasokController::class, 'show'])->name('admin.pemasok.show');
    Route::get('/admin/pemasok/{pemasok}/edit', [PemasokController::class, 'edit'])->name('admin.pemasok.edit');
    Route::put('/admin/pemasok/{pemasok}', [PemasokController::class, 'update'])->name('admin.pemasok.update');
    Route::delete('/admin/pemasok/{pemasok}', [PemasokController::class, 'destroy'])->name('admin.pemasok.destroy');

    Route::get('/admin/kategori', [KategoriController::class, 'index'])->name('admin.kategori'); // List kategori
    Route::get('/admin/kategori/create', [KategoriController::class, 'create'])->name('admin.kategori.create'); // Form tambah kategori
    Route::post('/admin/kategori', [KategoriController::class, 'store'])->name('admin.kategori.store'); // Simpan kategori
    Route::get('/admin/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('admin.kategori.edit'); // Form edit kategori
    Route::put('/admin/kategori/{id}', [KategoriController::class, 'update'])->name('admin.kategori.update'); // Update kategori
    Route::delete('/admin/kategori/{id}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy'); // Hapus kategori

    Route::get('/admin/pembelian', [PembelianController::class, 'index'])->name('admin.pembelian');
    Route::get('/admin/pembelian/create', [PembelianController::class, 'create'])->name('admin.pembelian.create');
    Route::post('/admin/pembelian', [PembelianController::class, 'store'])->name('admin.pembelian.store');
    Route::get('/admin/pembelian/{id}', [PembelianController::class, 'show'])->name('admin.pembelian.show');
    Route::delete('/admin/pembelian/{id}', [PembelianController::class, 'destroy'])->name('admin.pembelian.destroy');
    Route::get('/admin/pembelian/barang/{pemasok_id}', [PembelianController::class, 'getBarangByPemasok']);

    Route::get('/admin/penjualan', [PenjualanController::class, 'index'])->name('admin.penjualan');
    Route::get('/admin/penjualan/create', [PenjualanController::class, 'create'])->name('admin.penjualan.create');
    Route::post('/admin/penjualan/store', [PenjualanController::class, 'store'])->name('admin.penjualan.store');
    Route::get('/admin/penjualan/{id}', [PenjualanController::class, 'show'])->name('admin.penjualan.show');
    Route::delete('/admin/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('admin.penjualan.destroy');
    Route::post('/admin/penjualan/create/struk', [PenjualanController::class, 'cetakStruk'])->name('admin.penjualan.struk');


    Route::get('/admin/barangs', [BarangController::class, 'index'])->name('admin.barang');
    Route::get('/admin/barangs/create', [BarangController::class, 'create'])->name('admin.barang.create');
    Route::post('/admin/barangs', [BarangController::class, 'store'])->name('admin.barang.store');
    Route::get('/admin/barangs/{barang}', [BarangController::class, 'show'])->name('admin.barang.show');
    Route::get('/admin/barangs/{barang}/edit', [BarangController::class, 'edit'])->name('admin.barang.edit');
    Route::put('/admin/barangs/{barang}', [BarangController::class, 'update'])->name('admin.barang.update');
    Route::delete('/admin/barangs/{barang}', [BarangController::class, 'destroy'])->name('admin.barang.destroy');
});
Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/kasir/dashboard', [HomeController::class, 'kasirDashboard'])->name('kasir.dashboard');

    Route::get('/kasir/pembelian', [PembelianController::class, 'index'])->name('kasir.pembelian');
    Route::get('/kasir/pembelian/create', [PembelianController::class, 'create'])->name('kasir.pembelian.create');
    Route::post('/kasir/pembelian', [PembelianController::class, 'store'])->name('kasir.pembelian.store');
    Route::get('/kasir/pembelian/{id}', [PembelianController::class, 'show'])->name('kasir.pembelian.show');
    Route::delete('/kasir/pembelian/{id}', [PembelianController::class, 'destroy'])->name('kasir.pembelian.destroy');
    Route::get('/kasir/pembelian/barang/{pemasok_id}', [PembelianController::class, 'getBarangByPemasok']);

    Route::get('/kasir/penjualan', [PenjualanController::class, 'index'])->name('kasir.penjualan');
    Route::get('/kasir/penjualan/create', [PenjualanController::class, 'create'])->name('kasir.penjualan.create');
    Route::post('/kasir/penjualan/store', [PenjualanController::class, 'store'])->name('kasir.penjualan.store');
    Route::get('/kasir/penjualan/{id}', [PenjualanController::class, 'show'])->name('kasir.penjualan.show');

    Route::delete('/kasir/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('kasir.penjualan.destroy');

    Route::get('/kasir/barangs', [BarangController::class, 'index'])->name('kasir.barang');
    Route::get('/kasir/barangs/create', [BarangController::class, 'create'])->name('kasir.barang.create');
    Route::post('/kasir/barangs', [BarangController::class, 'store'])->name('kasir.barang.store');
    Route::get('/kasir/barangs/{barang}', [BarangController::class, 'show'])->name('kasir.barang.show');
    Route::get('/kasir/barangs/{barang}/edit', [BarangController::class, 'edit'])->name('kasir.barang.edit');
    Route::put('/kasir/barangs/{barang}', [BarangController::class, 'update'])->name('kasir.barang.update');
    Route::delete('/kasir/barangs/{barang}', [BarangController::class, 'destroy'])->name('kasir.barang.destroy');


    Route::get('/kasir/kategori', [KategoriController::class, 'index'])->name('kasir.kategori'); // List kategori
    Route::get('/kasir/kategori/create', [KategoriController::class, 'create'])->name('kasir.kategori.create'); // Form tambah kategori
    Route::post('/kasir/kategori', [KategoriController::class, 'store'])->name('kasir.kategori.store'); // Simpan kategori
    Route::get('/kasir/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kasir.kategori.edit'); // Form edit kategori
    Route::put('/kasir/kategori/{id}', [KategoriController::class, 'update'])->name('kasir.kategori.update'); // Update kategori
    Route::delete('/kasir/kategori/{id}', [KategoriController::class, 'destroy'])->name('kasir.kategori.destroy'); // Hapus kategori
});
Route::middleware(['auth', 'role:pemilik'])->group(function () {
    Route::get('/pemilik/dashboard', [HomeController::class, 'pemilikDashboard'])->name('pemilik.dashboard');

    Route::get('/pemilik/laporan', [LaporanController::class, 'index'])->name('pemilik.laporan.index');
    Route::get('/pemilik/laporan/penjualan', [LaporanController::class, 'laporanPenjualan'])->name('pemilik.laporan.penjualan');
    Route::get('/pemilik/laporan/pembelian', [LaporanController::class, 'laporanPembelian'])->name('pemilik.laporan.pembelian');
});



Route::prefix('admin')->group(function () {
    Route::get('/pengajuan-barang', [PengajuanBarangController::class, 'index'])->name('pengajuan-barang.index');
    Route::get('/pengajuan-barang/create', [PengajuanBarangController::class, 'create'])->name('pengajuan-barang.create');
    Route::post('/pengajuan-barang', [PengajuanBarangController::class, 'store'])->name('pengajuan-barang.store');
    Route::get('/pengajuan-barang/{id}/edit', [PengajuanBarangController::class, 'edit'])->name('pengajuan-barang.edit');
    Route::put('/pengajuan-barang/{id}', [PengajuanBarangController::class, 'update'])->name('pengajuan-barang.update');
    Route::delete('/pengajuan-barang/{id}', [PengajuanBarangController::class, 'destroy'])->name('pengajuan-barang.destroy');
    Route::post('/pengajuan-barang/update-terpenuhi/{id}', [PengajuanBarangController::class, 'updateTerpenuhi'])->name('pengajuan.update-terpenuhi');
    // Route::get('/pengajuan/export/pdf', [PengajuanBarangController::class, 'exportPDF'])->name('pengajuan.export.pdf');
});


//admin
Route::get('absensi-kerja', [AbsensiKerjaController::class, 'index'])->name('absensi-kerja.index');
Route::post('absensi-kerja', [AbsensiKerjaController::class, 'store'])->name('absensi-kerja.store');
Route::put('absensi-kerja/{id}', [AbsensiKerjaController::class, 'update'])->name('absensi-kerja.update');
Route::delete('absensi-kerja/{id}', [AbsensiKerjaController::class, 'destroy'])->name('absensi-kerja.destroy');
Route::get('absensi-kerja/{id}/edit', [AbsensiKerjaController::class, 'edit'])->name('absensi-kerja.edit');

Route::post('absensi-kerja/update-status', [AbsensiKerjaController::class, 'updateStatus'])->name('absensi-kerja.update-status');
Route::post('absensi-kerja/selesai-kerja', [AbsensiKerjaController::class, 'selesaiKerja'])->name('absensi-kerja.selesai-kerja');
Route::get('absensi-kerja/export/excel', [AbsensiKerjaController::class, 'exportExcel'])->name('absensi-kerja.export.excel');
Route::get('absensi-kerja/export/pdf', [AbsensiKerjaController::class, 'exportPdf'])->name('absensi-kerja.export.pdf');
Route::post('/absensi-kerja/import', [AbsensiKerjaController::class, 'import'])->name('absensi-kerja.import');
