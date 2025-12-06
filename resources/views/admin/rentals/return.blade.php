<x-app-layout>
    <div class="max-w-3xl mx-auto">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.rentals.show', $rental->id) }}" class="p-3 bg-white rounded-2xl shadow-sm text-slate-400 hover:text-rose-500 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Proses Pengembalian</h1>
                <p class="text-slate-400 text-sm">Cek kondisi barang sebelum konfirmasi.</p>
            </div>
        </div>

        <form action="{{ route('admin.rentals.process_return', $rental->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-50">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                    Cek Keterlambatan (Sistem)
                </h3>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Status Waktu</label>
                        @if($daysLate > 0)
                            <div class="text-rose-500 font-bold text-lg">Terlambat {{ $daysLate }} Hari</div>
                        @else
                            <div class="text-emerald-500 font-bold text-lg">Tepat Waktu</div>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Denda Telat (Estimasi)</label>
                        <div class="text-slate-700 font-bold text-lg">Rp {{ number_format($lateFeeEstimate) }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-50">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </span>
                    Cek Fisik & Kerusakan (Manual)
                </h3>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-600 mb-2">Biaya Kerusakan / Kehilangan (Opsional)</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>
                            <input type="number" name="damage_fee"
                                class="w-full pl-14 pr-5 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-bold text-lg placeholder-slate-300"
                                placeholder="0">
                        </div>
                        <p class="text-xs text-slate-400 mt-2">Isi hanya jika ada barang yang rusak, hilang, atau kotor.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-600 mb-2">Keterangan Kerusakan</label>
                        <textarea name="damage_reason" rows="3"
                            class="w-full px-5 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-medium placeholder-slate-400"
                            placeholder="Contoh: Lensa baret, tutup kamera hilang..."></textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4">
                <div class="text-sm text-slate-500">
                    *Total Denda = Denda Telat + Biaya Kerusakan
                </div>
                <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-8 py-4 rounded-xl font-bold shadow-lg shadow-rose-200 transition transform hover:-translate-y-1">
                    Simpan & Selesaikan Transaksi
                </button>
            </div>

        </form>
    </div>
</x-app-layout>