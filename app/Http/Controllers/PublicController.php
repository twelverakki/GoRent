<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    public function index()
    {
        // Ambil 4 barang terbaru untuk ditampilkan di Homepage
        $tools = Tool::with('category')
                    ->where('stock', '>', 0)
                    ->where('is_available', true)
                    ->latest()
                    ->take(4)
                    ->get();

        return view('welcome', compact('tools'));
    }

    public function tools()
    {
        $tools = Tool::with('category')
                    ->latest()
                    ->paginate(12);

        // Ambil data kategori untuk sidebar/filter
        $categories = Category::all();

        return view('catalog', compact('tools', 'categories'));
    }

    // Halaman Detail Alat
    // Kita pakai binding 'slug' biar URL-nya cantik (gorent.com/alat/sony-a7)
    public function show(Tool $tool)
    {
        // Pastikan kita muat kategori agar bisa ditampilkan
        $user = Auth::user();
        $tool->load('category');

        // Cukup kirim $tool saja.
        // Data user ambil langsung di Blade pakai Auth::user()
        return view('tool-detail', compact('tool', 'user'));
    }
}