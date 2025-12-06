<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Menambahkan barang ke keranjang (Session)
     */
    public function add(Request $request, $toolId)
    {
        // Ambil data alat dari database
        $tool = Tool::findOrFail($toolId);

        // Ambil keranjang saat ini (jika belum ada, array kosong)
        $cart = session()->get('cart', []);

        // Cek apakah barang ini sudah ada di keranjang?
        if(isset($cart[$toolId])) {
            // Jika sudah ada, jangan double, biarkan saja (atau bisa qty++)
            // Di kasus rental, biasanya cukup notify kalau sudah ada
            return redirect()->back()->with('info', 'Barang ini sudah ada di keranjang.');
        } else {
            // Jika belum ada, masukkan ke array
            $cart[$toolId] = [
                "name" => $tool->name,
                "quantity" => 1,
                "price" => $tool->price_per_day,
                "image" => $tool->image
            ];
        }

        // Simpan kembali ke session
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Berhasil ditambahkan ke keranjang!');
    }

    /**
     * Menghapus barang dari keranjang
     */
    public function remove($toolId)
    {
        $cart = session()->get('cart');

        if(isset($cart[$toolId])) {
            unset($cart[$toolId]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Barang dihapus dari keranjang.');
    }
}