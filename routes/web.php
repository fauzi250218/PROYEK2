<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\TagihanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login_admin');
});

Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login_admin');
Route::post('/login', [AdminController::class, 'login']);
Route::post('/logout', [AdminController::class, 'logout'])->name('logout_admin');
Route::get('/beranda', [AdminController::class, 'showBeranda'])->name('beranda_admin');
Route::get('/paket', [PaketController::class, 'index'])->name('paket.index');
Route::get('/paket/create', [PaketController::class, 'create'])->name('paket.create');
Route::post('/paket', [PaketController::class, 'store'])->name('paket.store');
Route::get('/paket/{id}/edit', [PaketController::class, 'edit'])->name('paket.edit');
Route::put('/paket/{id}', [PaketController::class, 'update'])->name('paket.update');
Route::delete('/paket/{id}', [PaketController::class, 'destroy'])->name('paket.destroy');
Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
Route::get('/pengguna/create', [PenggunaController::class, 'create'])->name('pengguna.create');
Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit');
Route::put('/pengguna/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');
Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
Route::get('/pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
Route::get('/pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
Route::put('/pelanggan/{id}', [PelangganController::class, 'update'])->name('pelanggan.update');
Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
Route::get('/sinkron', [PelangganController::class, 'sinkronDataLama']);
Route::get('/kas', [KasController::class, 'index'])->name('kas.index');
Route::get('/kas/create', [KasController::class, 'create'])->name('kas.create');
Route::post('/kas', [KasController::class, 'store'])->name('kas.store');
Route::get('/kas/{id}/edit', [KasController::class, 'edit'])->name('kas.edit');
Route::put('/kas/{id}', [KasController::class, 'update'])->name('kas.update');
Route::delete('/kas/{id}', [KasController::class, 'destroy'])->name('kas.destroy');
Route::get('/laporan/kas', [KasController::class, 'laporanForm'])->name('kas.laporan.form');
Route::get('/laporan/kas/cetak', [KasController::class, 'laporanCetak'])->name('kas.laporan.cetak');
Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
Route::get('/tagihan/create', [TagihanController::class, 'create'])->name('tagihan.create');
Route::post('/tagihan', [TagihanController::class, 'store'])->name('tagihan.store');
Route::patch('/tagihan/{id}/status', [TagihanController::class, 'updateStatus'])->name('tagihan.tandaiLunas');
Route::get('tagihan/{id}/cetak', [TagihanController::class, 'cetak'])->name('tagihan.cetak');
Route::get('/tagihan/{id}/kirimwa', [TagihanController::class, 'kirimwa'])->name('tagihan.kirimwa');

