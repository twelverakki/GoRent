<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Fine;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    protected $waService;

    public function __construct(WhatsAppService $waService)
    {
        $this->waService = $waService;
    }

    public function index(Request $request)
    {
        // Query Dasar
        $query = Rental::with(['user', 'items.tool'])->latest();

        // 1. Filter Search (Invoice / Nama User)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_no', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // 2. Filter Tab Status (Pending, Active, Completed, dll)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $rentals = $query->paginate(10);
        $statuses = ['' => 'Semua'] + Rental::STATUS_LABELS;

        return view('admin.rentals.index', compact('rentals', 'statuses'));
    }

    public function show(Rental $rental)
    {
        // Load relasi user dan item
        $rental->load(['user', 'items.tool', 'fine']);

        return view('admin.rentals.show', compact('rental'));
    }

    /**
     * Tampilkan Form Pengembalian (Preview Denda)
     */
    public function edit(Rental $rental)
    {
        // Hitung simulasi denda keterlambatan untuk ditampilkan ke Admin
        $actualReturnDate = Carbon::now();
        $planReturnDate = $rental->end_date;
        $daysLate = 0;
        $lateFeeEstimate = 0;

        if ($actualReturnDate->gt($planReturnDate)) {
            // Hitung hari telat (minimal 1 hari jika lewat jam)
            $daysLate = $actualReturnDate->diffInDays($planReturnDate);
            if ($daysLate == 0) $daysLate = 1;

            // Hitung estimasi biaya per item
            foreach ($rental->items as $item) {
                // Ambil tarif denda alat, atau 50% harga sewa jika belum disetting
                $feePerItem = $item->tool->late_fee_per_day ?? ($item->tool->price_per_day * 0.5);
                $lateFeeEstimate += $feePerItem * $item->quantity * $daysLate;
            }
        }

        return view('admin.rentals.return', compact('rental', 'daysLate', 'lateFeeEstimate'));
    }

    /**
     * Proses Simpan Pengembalian & Denda
     */
    public function update(Request $request, Rental $rental)
    {
        // Validasi input manual (Damage Fee)
        $request->validate([
            'damage_fee' => 'nullable|numeric|min:0',
            'damage_reason' => 'nullable|string',
        ]);

        // Pastikan relasi user dimuat sebelum transaksi untuk notifikasi
        $rental->load('user');
        $customer = $rental->user;
        $customerPhone = $customer->phone ?? env('ADMIN_WA_NUMBER');

        DB::beginTransaction();
        try {
            // 1. Hitung Ulang Denda Telat (Untuk kepastian data saat submit)
            $actualReturnDate = Carbon::now();
            $daysLate = 0;
            $systemFine = 0;
            $lateReason = "";

            if ($actualReturnDate->gt($rental->end_date)) {
                $daysLate = $actualReturnDate->diffInDays($rental->end_date);
                if ($daysLate == 0) $daysLate = 1;

                foreach ($rental->items as $item) {
                    $feePerItem = $item->tool->late_fee_per_day ?? ($item->tool->price_per_day * 0.5);
                    $systemFine += $feePerItem * $item->quantity * $daysLate;
                }
                $lateReason = "[Telat {$daysLate} Hari: Rp " . number_format($systemFine) . "] ";
            }

            // 2. Ambil Denda Kerusakan (Manual Input)
            $damageFee = $request->damage_fee ?? 0;
            $damageReason = $request->damage_reason ? "[Kerusakan: {$request->damage_reason}]" : "";

            // 3. Total Denda
            $totalFine = $systemFine + $damageFee;
            $finalReason = $lateReason . $damageReason;

            // Jika ada denda (baik telat maupun rusak), buat record Fine
            if ($totalFine > 0) {
                Fine::create([
                    'rental_id' => $rental->id,
                    'amount' => $totalFine,
                    'reason' => $finalReason ?: 'Denda Lainnya',
                    'status' => 'unpaid',
                ]);
            }

            // 4. Update Status Rental jadi Completed
            $rental->update([
                'status' => 'completed',
                'return_date' => $actualReturnDate,
            ]);

            // 5. RESTOCK (Kembalikan jumlah stok alat)
            foreach ($rental->items as $item) {
                $item->tool->increment('stock', $item->quantity);
            }

            DB::commit();

            // ===============================================
            // 💡 NOTIFIKASI WHATSAPP UNTUK CUSTOMER 💡
            // ===============================================
            $message = "✅ *PENGEMBALIAN SELESAI* ✅\n\n"
                    . "Halo {$customer->name},\n"
                    . "Pengembalian invoice *{$rental->invoice_no}* telah berhasil diproses.\n";

            if ($totalFine > 0) {
                $message .= "\n⚠️ *ANDA DIKENAKAN DENDA* ⚠️\n"
                        . "Total Denda: *Rp " . number_format($totalFine, 0, ',', '.') . "*\n"
                        . "Rincian Denda: {$finalReason}\n\n"
                        . "Silakan segera lunasi denda tersebut.";
            } else {
                $message .= "\nTotal denda: *Rp 0 (Nihil)*. Terima kasih telah menyewa tepat waktu!\n";
            }

            $message .= "\nSalam dari tim GoRent.";

            $this->waService->sendMessage($customerPhone, $message);
            // ===============================================

            // Redirect ke halaman detail lagi, tapi statusnya sudah completed
            return redirect()->route('admin.rentals.show', $rental->id)
                ->with('success', 'Pengembalian berhasil diproses!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Rental $rental)
    {
        // 1. Validasi Input Status
        $request->validate([
            'status' => 'required|in:active, completed, cancelled, overdue'
        ]);

        $newStatus = $request->status;

        try {
            // 2. Update Status Rental
            $rental->update([
                'status' => $newStatus,
            ]);

            // Pastikan data user tersedia untuk notifikasi
            $rental->load('user');
            $customer = $rental->user;
            $customerPhone = $customer->phone ?? env('ADMIN_WA_NUMBER'); // Fallback ke nomor Admin jika nomor customer hilang

            $message = '';

            // ===============================================
            // 💡 LOGIKA WA BERDASARKAN STATUS BARU 💡
            // ===============================================
            if ($newStatus === 'paid') {
                // Status: Pembayaran Diterima
                $message = "🎉 *Pembayaran Dikonfirmasi!* 🎉\n\n"
                         . "Halo {$customer->name}, pembayaran Anda untuk invoice *{$rental->invoice_no}* telah kami terima.\n"
                         . "Total: Rp " . number_format($rental->total_price, 0, ',', '.') . "\n"
                         . "Alat siap diambil pada tanggal *{$rental->start_date->format('d/m/Y')}*.\n"
                         . "Terima kasih!";

            } elseif ($newStatus === 'cancelled') {
                // Status: Pesanan Dibatalkan
                $message = "⚠️ *Pemesanan Dibatalkan* ⚠️\n\n"
                         . "Halo {$customer->name}, pesanan Anda dengan invoice *{$rental->invoice_no}* telah dibatalkan oleh Admin.\n"
                         . "Mohon hubungi Admin kami jika Anda memiliki pertanyaan.";
            }

            // Kirim pesan hanya jika ada pesan yang perlu dikirim (status = paid atau cancelled)
            if ($message) {
                $this->waService->sendMessage($customerPhone, $message);
            }
            // ===============================================


            // 3. Redirect dengan pesan sukses
            return redirect()->route('admin.rentals.show', $rental->id)
                             ->with('success', "Status Invoice {$rental->invoice_no} berhasil diperbarui menjadi '{$newStatus}'.");

        } catch (\Exception $e) {
            // Tangani error jika update gagal
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

}