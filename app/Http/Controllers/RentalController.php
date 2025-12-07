<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\Tool;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class RentalController extends Controller
{
    protected $waService;

    // Inject WhatsAppService agar bisa dipakai di method store
    public function __construct(WhatsAppService $waService)
    {
        $this->waService = $waService;
    }

    public function index()
    {
        // Menampilkan 4 alat di halaman depan
        $tools = Tool::with('category')
                    ->where('stock', '>', 0)
                    ->latest()
                    ->take(4)
                    ->get();

        return view('welcome', compact('tools'));
    }

    public function create()
    {
        $cart = session()->get('cart', []);

        // Cek jika keranjang kosong
        if(empty($cart)) {
            return redirect()->route('catalog')->with('error', 'Keranjang masih kosong.');
        }

        $tools = Tool::whereIn('id', array_keys($cart))->get();

        return view('rentals.create', compact('tools'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date', // Gunakan after start_date
            'tools' => 'required|array',
            'tools.*.id' => 'required|exists:tools,id',
            'tools.*.qty' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            // Hitung selisih hari (minimal 1 hari)
            $duration = $startDate->diffInDays($endDate) ?: 1;

            // 2. Buat Header Transaksi
            $rental = Rental::create([
                'user_id' => Auth::id(),
                'invoice_no' => 'INV-' . strtoupper(Str::random(10)),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_price' => 0, // Nanti diupdate
                'status' => 'pending',
            ]);

            $totalPrice = 0;

            // 3. Loop Item & Kurangi Stok
            foreach ($request->tools as $itemData) {
                $tool = Tool::lockForUpdate()->find($itemData['id']); // Lock baris biar aman dari race condition

                if ($tool->stock < $itemData['qty']) {
                    throw new \Exception("Stok {$tool->name} tidak mencukupi.");
                }

                $subtotal = $tool->price_per_day * $duration * $itemData['qty'];
                $totalPrice += $subtotal;

                RentalItem::create([
                    'rental_id' => $rental->id,
                    'tool_id' => $tool->id,
                    'quantity' => $itemData['qty'],
                    'price_snapshot' => $tool->price_per_day,
                    'subtotal' => $subtotal,
                ]);

                $tool->decrement('stock', $itemData['qty']);
            }

            // Update Total Harga
            $rental->update(['total_price' => $totalPrice]);

            DB::commit();

            // ===============================================
            // 💡 4. LOGIKA NOTIFIKASI WHATSAPP
            // ===============================================

            // A. Kirim ke Customer
            $user = Auth::user();
            // Fallback jika user belum isi no hp, pakai nomor admin agar tidak error/crash
            $customerPhone = $user->phone ?? env('ADMIN_WA_NUMBER');

            $customerMsg = "Halo *{$user->name}*! 👋\n\n"
                         . "Booking kamu berhasil dibuat!\n"
                         . "------------------------------\n"
                         . "Invoice: *{$rental->invoice_no}*\n"
                         . "Total: *Rp " . number_format($rental->total_price, 0, ',', '.') . "*\n"
                         . "Durasi: {$duration} Hari\n"
                         . "------------------------------\n"
                         . "Silakan lakukan pembayaran dan konfirmasi ke Admin.";

            $this->waService->sendMessage($customerPhone, $customerMsg);

            // B. Kirim ke Admin
            $adminPhone = env('ADMIN_WA_NUMBER');
            if ($adminPhone) {
                $adminMsg = "🔔 *ORDER BARU MASUK* 🔔\n\n"
                          . "Customer: {$user->name}\n"
                          . "Invoice: {$rental->invoice_no}\n"
                          . "Total: Rp " . number_format($rental->total_price, 0, ',', '.') . "\n\n"
                          . "Mohon cek dashboard admin untuk verifikasi.";

                $this->waService->sendMessage($adminPhone, $adminMsg);
            }

            // Hapus session keranjang
            $request->session()->forget('cart');

            return redirect()->route('rentals.history')
                ->with('success', 'Booking berhasil! Cek WhatsApp kamu untuk detail pesanan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function paymentForm(Rental $rental)
    {
        // Validasi: User hanya bisa akses transaksinya sendiri & status harus pending/rejected
        if ($rental->user_id !== Auth::id()) {
            abort(403);
        }

        // Jika sudah lunas/active, tidak perlu bayar lagi
        if (!in_array($rental->status, ['pending'])) {
            return redirect()->route('rentals.history')->with('success', 'Transaksi ini sudah diproses.');
        }

        return view('rentals.payment', compact('rental'));
    }

    public function uploadPayment(Request $request, Rental $rental)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        if ($request->file('payment_proof')) {
            // 1. Simpan Gambar ke folder 'public/payments'
            $path = $request->file('payment_proof')->store('payments', 'public');

            // 2. Update Status jadi 'paid' (Menunggu Verifikasi)
            $rental->update([
                'payment_proof' => $path,
                'status' => 'paid',
                'rejection_reason' => null // Reset alasan tolak jika ada
            ]);

            // 3. Notifikasi WA ke Admin
            $adminPhone = env('ADMIN_WA_NUMBER');
            if($adminPhone) {
                $msg = "🔔 *KONFIRMASI PEMBAYARAN* 🔔\n\n" .
                       "Invoice: *{$rental->invoice_no}*\n" .
                       "Customer: {$rental->user->name}\n" .
                       "Total: Rp " . number_format($rental->total_price) . "\n\n" .
                       "Mohon cek dashboard admin untuk verifikasi bukti pembayaran.";

                $this->waService->sendMessage($adminPhone, $msg);
            }

            return redirect()->route('rentals.history')->with('success', 'Bukti pembayaran terkirim! Menunggu konfirmasi admin.');
        }

        return back()->with('error', 'Gagal upload gambar.');
    }

    public function history()
    {
        $rentals = Rental::where('user_id', Auth::id())
            ->with(['items.tool'])
            ->latest()
            ->paginate(10);

        return view('rentals.history', compact('rentals'));
    }
}