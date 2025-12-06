<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ToolController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data kategori untuk filter
        $categories = Category::all();

        // 2. Query Tools
        $query = Tool::with('category')->latest();

        // Logika Filter
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Ubah logic ini sedikit agar sesuai parameter di View nanti
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $tools = $query->paginate(10);

        // 3. Kirim variabel $categories ke view
        return view('admin.tools.index', compact('tools', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.tools.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Validasi UUID
            'price_per_day' => 'required|numeric|min:0',
            'late_fee_per_day' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(5);

        // Upload Gambar
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tools', 'public');
        }

        Tool::create($data);

        return redirect()->route('tools.index')->with('success', 'Alat berhasil ditambahkan');
    }

    public function edit(Tool $tool)
    {
        // Ambil semua kategori buat dropdown
        $categories = Category::all();

        return view('admin.tools.edit', compact('tool', 'categories'));
    }

    public function update(Request $request, Tool $tool)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price_per_day' => 'required|numeric|min:0',
            'late_fee_per_day' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'condition' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Gambar tidak wajib (nullable), karena user mungkin cuma mau edit harga/stok
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available' => 'boolean' // Menerima 0 atau 1 dari trik checkbox tadi
        ]);

        // 2. Ambil semua data request KECUALI gambar
        // Kita pisahkan logika gambar agar tidak menimpa gambar lama dengan null
        $data = $request->except(['image']);

        // 3. Update Slug (Opsional: Agar URL ikut berubah sesuai nama baru)
        // Contoh: "Sony A7" -> "sony-a7-x9s8d"
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(5);

        // 4. Logika Upload Gambar Baru
        if ($request->hasFile('image')) {

            // A. Hapus gambar lama dulu (biar hemat storage)
            if ($tool->image && Storage::disk('public')->exists($tool->image)) {
                Storage::disk('public')->delete($tool->image);
            }

            // B. Upload gambar baru
            $data['image'] = $request->file('image')->store('tools', 'public');
        }

        // 5. Simpan Perubahan ke Database
        $tool->update($data);

        return redirect()->route('admin.tools.index')->with('success', 'Data alat berhasil diperbarui!');
    }
}