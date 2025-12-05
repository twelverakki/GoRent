<?php

use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Route;

// 1. Area Customer
Route::middleware(['auth', 'role:customer'])->group(function () {

    // Cek denda dulu sebelum bisa sewa
    Route::middleware(['no.fines'])->group(function () {
        Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
    });

    Route::get('/my-rentals', [RentalController::class, 'history'])->name('rentals.history');
});

// 2. Area Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Kelola Barang
    Route::resource('tools', ToolController::class);

    // Proses Pengembalian (Return)
    Route::get('/rentals/{rental}/return', [ReturnController::class, 'edit'])->name('rentals.return');
    Route::put('/rentals/{rental}/return', [ReturnController::class, 'update'])->name('rentals.process_return');
});