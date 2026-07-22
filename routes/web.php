<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryaWargaController;
use App\Http\Controllers\BukuLokalController;
use App\Http\Controllers\AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - E-Pustaka JENDELA
|--------------------------------------------------------------------------
*/

// Portal Utama Publik (Warga Desa / Guest)
Route::get('/', function () {
    return view('index');
});

// Otentikasi Admin (Karang Taruna / Perangkat Desa)
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('logout');

// Area Terproteksi Admin (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/admin', function () {
        return redirect('/admin/kurasi');
    });
    Route::get('/admin/kurasi', [KaryaWargaController::class, 'halamanKurasi']);
    Route::get('/admin/buku', [BukuLokalController::class, 'halamanBuku']);
});
