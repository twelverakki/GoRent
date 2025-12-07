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
                    ->latest()
                    ->take(4)
                    ->get();

        return view('welcome', compact('tools'));
    }

    public function tools(Request $request)
    {
        $query = Tool::with('category');

        $query->when($request->search, function ($q) use ($request) {return $q->where('name', 'like', '%' . $request->search . '%');});

        $tools = $query->latest()->paginate(12)->withQueryString();

        return view('catalog', compact('tools'));
    }

    // Halaman Detail Alat
    public function show(Tool $tool)
    {
        $user = Auth::user();
        $tool->load('category');

        return view('tool-detail', compact('tool', 'user'));
    }
}