<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PemasokController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index'); // List kategori
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create'); // Form tambah kategori
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store'); // Simpan kategori
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit'); // Form edit kategori
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update'); // Update kategori
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy'); // Hapus kategori
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
Route::get('/barang/{barang}', [BarangController::class, 'show'])->name('barang.show');
Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');


Route::get('/pemasok', [PemasokController::class, 'index'])->name('pemasok.index');
Route::get('/pemasok/create', [PemasokController::class, 'create'])->name('pemasok.create');
Route::post('/pemasok', [PemasokController::class, 'store'])->name('pemasok.store');
Route::get('/pemasok/{pemasok}', [PemasokController::class, 'show'])->name('pemasok.show');
Route::get('/pemasok/{pemasok}/edit', [PemasokController::class, 'edit'])->name('pemasok.edit');
Route::put('/pemasok/{pemasok}', [PemasokController::class, 'update'])->name('pemasok.update');
Route::delete('/pemasok/{pemasok}', [PemasokController::class, 'destroy'])->name('pemasok.destroy');
