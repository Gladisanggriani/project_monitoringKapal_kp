<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KapalController;
use App\Http\Controllers\ProfileController;

// --- AKSES VIEWER / PUBLIK ---
Route::get('/', [KapalController::class, 'index'])->name('dashboard');

// Ini rute preview yang dicari oleh tombol dashboard Anda:
Route::get('/kapal/preview-export', [KapalController::class, 'previewExport'])->name('kapal.preview-export');
Route::get('/kapal/export', [KapalController::class, 'export'])->name('kapal.export');

// --- AKSES ADMIN (Wajib Login) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/kapal/create', [KapalController::class, 'create'])->name('kapal.create');
    Route::post('/kapal', [KapalController::class, 'store'])->name('kapal.store');
    Route::get('/kapal/{kapal}/edit', [KapalController::class, 'edit'])->name('kapal.edit');
    Route::put('/kapal/{kapal}', [KapalController::class, 'update'])->name('kapal.update');
    Route::delete('/kapal/{kapal}', [KapalController::class, 'destroy'])->name('kapal.destroy');
    Route::patch('/kapal/{id}/archive', [KapalController::class, 'archive'])->name('kapal.archive');

    // Profile Laravel Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
