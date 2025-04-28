<?php

use App\Http\Controllers\API\PaketApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// API Paket
Route::get('/paket', [PaketApiController::class, 'index']);
Route::get('/paket/{id}', [PaketApiController::class, 'show']);
