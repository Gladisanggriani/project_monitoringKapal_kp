<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KapalController;

// Taruh di baris paling bawah file, pastikan nama Controller-nya adalah KapalController
Route::get('/kapal/export-data', [KapalController::class, 'export'])->name('kapal.export');
// --- AKSES VIEWER (Publik - Tanpa Login) ---
// Halaman utama menampilkan dashboard monitoring kapal untuk publik
Route::get('/', [KapalController::class, 'index'])->name('dashboard');


 
// --- AKSES ADMIN (Wajib Login).. ---
Route::middleware(['auth'])->group(function () {
    // Pastikan baris-baris ini ada dan tidak terhapus:
    Route::get('/kapal/create', [KapalController::class, 'create'])->name('kapal.create');
    Route::post('/kapal', [KapalController::class, 'store'])->name('kapal.store');
    Route::get('/kapal/{kapal}/edit', [KapalController::class, 'edit'])->name('kapal.edit');
    Route::put('/kapal/{kapal}', [KapalController::class, 'update'])->name('kapal.update');
    Route::delete('/kapal/{kapal}', [KapalController::class, 'destroy'])->name('kapal.destroy');
    
});

Route::get('/kapal/preview-export', [KapalController::class, 'previewExport'])
    ->name('kapal.preview-export');

Route::get('/kapal/export-data', [KapalController::class, 'export'])
    ->name('kapal.export');

// Memuat rute autentikasi bawaan dari Laravel Breeze (Login, Register, Logout)
require __DIR__.'/auth.php';