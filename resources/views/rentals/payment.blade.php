<x-frontend-layout>
    <div class="py-12 pt-20 bg-gray-50 min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-[2rem] p-8 shadow-xl max-w-lg w-full border border-slate-100">

            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-slate-800">Kirim Bukti Pembayaran</h1>
                <p class="text-slate-400 text-sm mt-1">Invoice: {{ $rental->invoice_no }}</p>
            </div>

            <div class="bg-slate-50 p-6 rounded-2xl mb-6 border border-slate-100 text-center">
                <p class="text-sm text-slate-500 mb-1">Total Tagihan</p>
                <p class="text-2xl font-black text-rose-500">Rp {{ number_format($rental->total_price) }}</p>
            </div>

            <form action="{{ route('rentals.payment.upload', $rental->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Upload Foto Bukti Transfer</label>
                    <input type="file" name="payment_proof" required accept="image/*"
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-rose-50 file:text-rose-500 hover:file:bg-rose-100 transition cursor-pointer ring-1 ring-slate-200 rounded-xl">
                    <p class="text-xs text-slate-400 mt-2">Format: JPG, PNG. Maksimal 2MB.</p>
                </div>

                <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white py-3.5 rounded-xl font-bold shadow-lg shadow-rose-200 transition">
                    Kirim Bukti Pembayaran
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="{{ route('rentals.history') }}" class="text-slate-400 hover:text-slate-600 text-sm font-medium">Batal</a>
            </div>
        </div>
    </div>
</x-frontend-layout>