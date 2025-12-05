<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Fine;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    // Menampilkan form proses pengembalian
    public function edit($rentalId)
    {
        $rental = Rental::with('items.tool')->findOrFail($rentalId);
        return view('admin.rentals.return', compact('rental'));
    }

    // Proses simpan pengembalian
    public function update(Request $request, $rentalId)
    {
        $rental = Rental::with('items.tool')->findOrFail($rentalId);

        // 1. Validasi
        $request->validate([
            'fine_amount' => 'nullable|numeric|min:0',
            'fine_reason' => 'nullable|string|required_with:fine_amount',
        ]);

        DB::beginTransaction();
        try {
            // 2. Cek Keterlambatan Otomatis
            $actualReturnDate = Carbon::now();
            $planReturnDate = Carbon::parse($rental->end_date);

            $lateFine = 0;
            $lateReason = '';

            // Jika tanggal sekarang lebih besar dari rencana kembali
            if ($actualReturnDate->gt($planReturnDate)) {
                $daysLate = $actualReturnDate->diffInDays($planReturnDate);
                // Contoh: Denda 50rb per hari telat
                $lateFine = $daysLate * 50000;
                $lateReason = "Terlambat {$daysLate} hari. ";
            }

            // 3. Gabungkan Denda Kerusakan (Manual) + Denda Telat (Otomatis)
            $totalFine = $lateFine + ($request->fine_amount ?? 0);
            $totalReason = $lateReason . ($request->fine_reason ?? '');

            // Jika ada denda, buat record di tabel fines
            if ($totalFine > 0) {
                Fine::create([
                    'rental_id' => $rental->id,
                    'amount' => $totalFine,
                    'reason' => $totalReason,
                    'status' => 'unpaid', // User harus bayar terpisah
                ]);
            }

            // 4. Update Status Rental
            $rental->update([
                'status' => 'completed',
                'return_date' => $actualReturnDate,
            ]);

            // 5. RESTOCK (Kembalikan stok barang)
            foreach ($rental->items as $item) {
                // Kembalikan stok sesuai qty yg dipinjam
                $item->tool->increment('stock', $item->quantity);
            }

            DB::commit();
            return redirect()->route('admin.rentals.index')
                ->with('success', 'Barang berhasil dikembalikan & Stok diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }
}