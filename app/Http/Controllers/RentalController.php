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

class RentalController extends Controller
{
    protected $waService;

    public function __construct(WhatsAppService $waService)
    {
        $this->waService = $waService;
    }

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

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|different:start_date',
            'tools' => 'required|array',
            'tools.*.id' => 'required|exists:tools,id',
            'tools.*.qty' => 'required|integer|min:1',
        ]);

        try {
            // Mulai Transaksi Database
            DB::beginTransaction();

            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $duration = $startDate->diffInDays($endDate) ?: 1;

            // 2. Buat Header Transaksi (Rental)
            $rental = Rental::create([
                'user_id' => Auth::id(),
                'invoice_no' => 'INV-' . strtoupper(Str::random(10)),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_price' => 0,
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
                    'price_snapshot' => $tool->price_per_day,
                    'subtotal' => $subtotal,
                ]);

                // KURANGI STOK
                $tool->decrement('stock', $itemData['qty']);
            }

            // Update Total Harga di Header
            $rental->update(['total_price' => $totalPrice]);

            // Commit (Simpan Permanen)
            DB::commit();

            // ===============================================
            // 💡 NOTIFIKASI WHATSAPP UNTUK CUSTOMER
            // ===============================================
            $user = Auth::user();

            // Asumsi Model User memiliki field 'phone'
            // Jika tidak ada, kamu harus menambahkan kolom 'phone' ke tabel users
            $customerPhone = $user->phone ?? '6281234567890'; // ⚠️ Ganti dengan nomor Admin/Fallback jika $user->phone tidak tersedia

            $message = "Halo *{$user->name}*,\n\n"
                     . "Booking sewa alat Anda di GoRent berhasil dibuat!\n"
                     . "Invoice: *{$rental->invoice_no}*\n"
                     . "Total Biaya: *Rp " . number_format($rental->total_price, 0, ',', '.') . "*\n"
                     . "Mulai Sewa: {$rental->start_date->format('d/m/Y')}\n"
                     . "Pengembalian: {$rental->end_date->format('d/m/Y')}\n\n"
                     . "Segera lakukan pembayaran dan konfirmasi melalui link yang tersedia di riwayat transaksi Anda.";

            $this->waService->sendMessage($customerPhone, $message);
            // ===============================================

            // ===============================================
            // 💡 NOTIFIKASI WHATSAPP UNTUK ADMIN 💡
            // ===============================================
            $adminPhone = env('ADMIN_WA_NUMBER');

            if ($adminPhone) {
                $adminMessage = "🔔 *PESANAN BARU* 🔔\n\n"
                              . "Customer: *{$user->name}*\n"
                              . "Invoice: *{$rental->invoice_no}*\n"
                              . "Total: *Rp " . number_format($rental->total_price, 0, ',', '.') . "*\n"
                              . "Periode: {$rental->start_date->format('d/m/Y')} s/d {$rental->end_date->format('d/m/Y')}\n\n"
                              . "Status saat ini: PENDING BAYAR.\n"
                              . "Cek di: " . route('admin.rentals.show', $rental->id); // Tambahkan link ke detail nota Admin

                $this->waService->sendMessage($adminPhone, $adminMessage);
            }

            // Hapus Keranjang dari Session
            $request->session()->forget('cart');

            return redirect()->route('rentals.history')
                ->with('success', 'Booking berhasil! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            // Rollback (Batalkan semua perubahan jika ada error)
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function create()
    {
        // 1. Ambil data keranjang dari session
        $cart = session()->get('cart', []);

        // 2. Ambil detail barang dari Database berdasarkan ID yang ada di session
        // (Supaya data harga & stok selalu fresh dari DB, bukan dari cache session lama)
        $tools = Tool::whereIn('id', array_keys($cart))->get();

        return view('rentals.create', compact('tools'));
    }

    public function history()
    {
        // Ambil data rental milik user yang login
        // Urutkan dari yang terbaru
        $rentals = Rental::where('user_id', Auth::user()->id)
            ->with(['items.tool']) // Load relasi items & tool biar query ringan
            ->latest()
            ->paginate(10);

        return view('rentals.history', compact('rentals'));
    }
}