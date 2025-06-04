<?php

use App\Http\Controllers\API\PenggunaController;  // Menggunakan PenggunaController
use App\Http\Controllers\API\PaketApiController;  // Menambahkan PaketApiController
use App\Http\Controllers\API\PelangganController;  // Menambahkan PelangganController
use App\Http\Controllers\API\TagihanApiController;
use Illuminate\Support\Facades\Route;

// API Paket
Route::get('/paket', [PaketApiController::class, 'index']);  // Menampilkan daftar paket
Route::get('/paket/{id}', [PaketApiController::class, 'show']);  // Menampilkan detail paket berdasarkan ID

// API Pengguna
Route::post('/register', [PenggunaController::class, 'register']);  // Registrasi Pengguna
Route::post('/login', [PenggunaController::class, 'login']);  // Login Pengguna

// Profil Pengguna yang sedang login (dengan autentikasi)
Route::middleware('auth:sanctum')->get('/user', [PenggunaController::class, 'userProfile']);  // Profil Pengguna

// API Pendaftaran Paket Pelanggan
Route::post('/daftar-paket', [PelangganController::class, 'store']);  // Menangani pendaftaran paket pelanggan

Route::prefix('tagihan')->group(function () {
    Route::get('/', [TagihanApiController::class, 'index']);               // List tagihan (opsional filter id_pelanggan)
    Route::get('/{id}', [TagihanApiController::class, 'show']);           // Detail tagihan by id
    Route::post('/{id}/payment-token', [TagihanApiController::class, 'createPaymentToken']); // Generate midtrans snap token untuk bayar

    // Endpoint webhook midtrans notification (pastikan tidak butuh auth)
    Route::post('/midtrans/notification', [TagihanApiController::class, 'midtransNotification']);
});