<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'tools' => 'required|array', // Array berisi ID tools yg dipilih
            'tools.*.id' => 'required|exists:tools,id',
            'tools.*.qty' => 'required|integer|min:1',
        ]);

        try {
            // Mulai Transaksi Database
            DB::beginTransaction();

            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $duration = $startDate->diffInDays($endDate) ?: 1; // Minimal 1 hari

            // 2. Buat Header Transaksi (Rental)
            $rental = Rental::create([
                'user_id' => Auth::id(), // ID UUID User yg login
                'invoice_no' => 'INV-' . strtoupper(Str::random(10)),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_price' => 0, // Nanti diupdate setelah hitung item
                'status' => 'pending',
            ]);

            $totalPrice = 0;

            // 3. Loop Item yang disewa
            foreach ($request->tools as $itemData) {
                $tool = Tool::findOrFail($itemData['id']);

                // Cek Stok (Pencegahan backend)
                if ($tool->stock < $itemData['qty']) {
                    throw new \Exception("Stok {$tool->name} tidak mencukupi.");
                }

                // Hitung Subtotal (Harga master x Durasi x Qty)
                $subtotal = $tool->price_per_day * $duration * $itemData['qty'];
                $totalPrice += $subtotal;

                // Simpan ke rental_items
                RentalItem::create([
                    'rental_id' => $rental->id,
                    'tool_id' => $tool->id,
                    'quantity' => $itemData['qty'],
                    'price_snapshot' => $tool->price_per_day, // HARGA DIKUNCI DISINI
                    'subtotal' => $subtotal,
                ]);

                // KURANGI STOK
                $tool->decrement('stock', $itemData['qty']);
            }

            // Update Total Harga di Header
            $rental->update(['total_price' => $totalPrice]);

            // Commit (Simpan Permanen)
            DB::commit();

            return redirect()->route('rentals.history')
                ->with('success', 'Booking berhasil! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            // Rollback (Batalkan semua perubahan jika ada error)
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}