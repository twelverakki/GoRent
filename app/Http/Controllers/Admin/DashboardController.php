<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Tool;
use App\Models\User;
use App\Models\Fine;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil 5 Transaksi Terakhir (Untuk Widget Aktivitas)
        $recentRentals = Rental::with(['user', 'items.tool'])
                                ->latest()
                                ->take(5)
                                ->get();

        // 2. Hitung statistik untuk kartu-kartu di atasnya

        // Total Alat (Tidak Berubah)
        $totalTools = Tool::count();

        // 💡 PERHITUNGAN BARU: Total Revenue
        // Menghitung total_price dari semua rental yang statusnya 'completed'
        $totalRevenue = Rental::where('status', 'completed')->sum('total_price');

        // Total Pelanggan (Tidak Berubah)
        $totalCustomers = User::where('role', 'customer')->count();

        // Total Denda Belum Dibayar (Tidak Berubah)
        $unpaidFines = Fine::where('status', 'unpaid')->count();

        return view('admin.dashboard', compact(
            'recentRentals',
            'totalTools',
            // 💡 Mengganti 'activeRentals' dengan 'totalRevenue'
            'totalRevenue',
            'totalCustomers',
            'unpaidFines'
        ));
    }
}