<?php

use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| 1. GUEST ROUTES (Bisa Diakses Tanpa Login)
|--------------------------------------------------------------------------
*/

// Halaman Depan / Katalog Utama
Route::get('/', [PublicController::class, 'index'])->name('home');

// Halaman Detail Alat (URL cantik pakai Slug)
// Contoh: gorent.com/alat/kamera-sony-a7-iii
Route::get('/catalog', [PublicController::class, 'tools'])->name('public.tool.index');
Route::get('/catalog/{tool:id}', [PublicController::class, 'show'])->name('public.tool.show');


/*
|--------------------------------------------------------------------------
| 2. AUTHENTICATED ROUTES (Harus Login Dulu)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // --- A. PROFILE USER (Bawaan Breeze) ---
    // Penting: Jangan dihapus agar menu dropdown 'Edit Profile' berfungsi
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // --- B. AREA CUSTOMER (Penyewa) ---
    Route::middleware('role:customer')->group(function () {

        // Riwayat Sewa & Denda
        Route::get('/my-rentals', [RentalController::class, 'history'])->name('rentals.history');
        // Route::get('/my-fines', [RentalController::class, 'fines'])->name('fines.index'); // Opsional jika sudah dibuat

        // UPDATE ROUTE CHECKOUT (Hapus parameter create)
        Route::middleware(['no.fines'])->group(function () {
            Route::get('/checkout', [RentalController::class, 'create'])->name('rentals.create'); // Tidak butuh parameter ID lagi
            Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
        });

        // CART ROUTES
        Route::get('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
        Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
        // Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    });


    // --- C. AREA ADMIN (Pengelola) ---
    // Semua route di sini otomatis punya prefix '/admin' dan nama 'admin.'
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Dashboard Statistik Admin
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Manajemen Inventaris Alat (CRUD Lengkap)
        Route::resource('tools', ToolController::class);

        // 💡 TAMBAHKAN INI: Manajemen Pengguna (CRUD)
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);

        // Manajemen Transaksi & Pengembalian
        Route::get('/rentals', [ReturnController::class, 'index'])->name('rentals.index');       // List Semua
        Route::get('/rentals/{rental}', [ReturnController::class, 'show'])->name('rentals.show'); // Detail Nota
        Route::put('/rentals/{rental}', [ReturnController::class, 'updateStatus'])->name('rentals.update'); // Update Pesanan

        // Proses Return & Denda
        // 1. UPDATE STATUS (Misalnya: Konfirmasi Bayar / admin.rentals.update)
        // Route ini memanggil metode updateStatus() yang baru kita buat.
        Route::put('/rentals/{rental:id}', [ReturnController::class, 'updateStatus'])->name('rentals.update');
        Route::get('/rentals/{rental}/return', [ReturnController::class, 'edit'])->name('rentals.return');
        Route::put('/rentals/{rental}/return', [ReturnController::class, 'update'])->name('rentals.process_return');
    });

});

// Load file auth.php (Login, Register, Logout)
require __DIR__.'/auth.php';