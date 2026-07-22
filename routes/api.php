<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryaWargaController;
use App\Http\Controllers\BukuLokalController;

/*
|--------------------------------------------------------------------------
| API Routes - E-Pustaka JENDELA
|--------------------------------------------------------------------------
*/

// API Karya Warga Desa
Route::get('/karya', [KaryaWargaController::class, 'index']);
Route::post('/karya', [KaryaWargaController::class, 'store']);
Route::patch('/karya/{id}/setujui', [KaryaWargaController::class, 'setujui']);
Route::delete('/karya/{id}', [KaryaWargaController::class, 'destroy']);

// API Buku Lokal Desa (E-Book/PDF)
Route::get('/buku-lokal', [BukuLokalController::class, 'index']);
Route::post('/buku-lokal', [BukuLokalController::class, 'store']);
Route::delete('/buku-lokal/{id}', [BukuLokalController::class, 'destroy']);
