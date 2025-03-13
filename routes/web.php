<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login_admin');
Route::post('/login', [AdminController::class, 'login']);
Route::post('/logout', [AdminController::class, 'logout'])->name('logout_admin');
Route::get('/beranda', [AdminController::class, 'showBeranda'])->name('beranda_admin');
