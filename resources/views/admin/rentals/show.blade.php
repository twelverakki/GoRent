<x-app-layout>
    <div class="max-w-4xl mx-auto">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6 font-medium border border-green-200 shadow-md">{{ session('success') }}</div>
        @endif

        {{-- 💡 Blok Notifikasi untuk Pesan ERROR --}}
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6 font-medium border border-red-200 shadow-md">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ url()->previous() === url()->current() ? route('admin.rentals.index') : url()->previous() }}" class="p-3 bg-white rounded-2xl shadow-sm text-slate-400 hover:text-rose-500 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Detail Transaksi</h1>
                <p class="text-slate-400 text-sm">Invoice: #{{ $rental->invoice_no }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-6">Barang Disewa</h3>
                    <div class="space-y-4">
                        @foreach($rental->items as $item)
                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <img src="{{ Storage::url($item->tool->image) }}" class="w-16 h-16 rounded-xl object-cover bg-white">
                            <div>
                                <h4 class="font-bold text-slate-700">{{ $item->tool->name }}</h4>
                                <p class="text-sm text-slate-500">Qty: {{ $item->quantity }} x Rp {{ number_format($item->price_snapshot) }}</p>
                            </div>
                            <div class="ml-auto font-bold text-slate-800">
                                Rp {{ number_format($item->subtotal) }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-center mt-6 pt-6 border-t border-slate-100">
                        <span class="text-slate-500 font-medium">Total Tagihan</span>
                        <span class="text-2xl font-bold text-rose-500">Rp {{ number_format($rental->total_price) }}</span>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://ui-avatars.com/api/?name={{ $rental->user->name }}&background=random&color=fff" class="w-12 h-12 rounded-full border border-slate-100">

                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Data Peminjam</h3>
                            <p class="text-xs text-slate-400">Customer ({{ ucfirst($rental->user->role) }})</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4 text-sm">

                        <div>
                            <p class="text-slate-400 mb-1 text-xs uppercase tracking-wider font-bold">Nama Lengkap</p>
                            <p class="font-bold text-slate-700 text-base">{{ $rental->user->name }}</p>
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1 text-xs uppercase tracking-wider font-bold">Email</p>
                            <p class="font-bold text-slate-700 break-all">{{ $rental->user->email }}</p>
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1 text-xs uppercase tracking-wider font-bold">Nomor WhatsApp</p>
                            <div class="flex items-center gap-2">
                                <span class="p-1.5 bg-green-100 text-green-600 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </span>
                                <p class="font-bold text-slate-700">{{ $rental->user->phone ?? '-' }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1 text-xs uppercase tracking-wider font-bold">Alamat Domisili</p>
                            <p class="font-bold text-slate-700 leading-snug">
                                {{ $rental->user->address ?? 'Alamat belum diisi' }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-[2rem] p-8 shadow-sm flex flex-col">
                    <h3 class="text-lg font-bold text-slate-800 mb-6">Status Sewa</h3>

                    <div class="mb-8">
                        @php
                            $statusColor = match($rental->status) {
                                'pending' => 'bg-yellow-100 text-yellow-600',
                                'active' => 'bg-emerald-100 text-emerald-600',
                                'completed' => 'bg-slate-100 text-slate-600',
                                default => 'bg-gray-100 text-gray-600'
                            };
                        @endphp
                        <span class="inline-block w-full text-center py-3 rounded-xl font-bold {{ $statusColor }}">
                            {{ strtoupper($rental->status) }}
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Tgl Sewa</span>
                            <span class="font-bold text-slate-700">{{ $rental->start_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Tgl Kembali</span>
                            <span class="font-bold text-slate-700">{{ $rental->end_date->format('d M Y') }}</span>
                        </div>
                    </div>

                   <div class="mt-auto pt-8">

                    {{-- 1. FLOW VERIFIKASI PEMBAYARAN (Status: PAID) --}}
                        @if($rental->status == 'paid')
                            <div class="bg-orange-50 border border-orange-100 p-4 rounded-xl mb-4">
                                <h4 class="font-bold text-orange-700 mb-1 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Verifikasi Pembayaran
                                </h4>
                                <p class="text-xs text-orange-600">Customer telah upload bukti. Silakan cek foto lalu tentukan aksi.</p>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                {{-- Tombol Terima --}}
                                <form action="{{ route('admin.rentals.update', $rental->id) }}" method="POST" onsubmit="return confirm('Yakin bukti pembayaran valid?')">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="active">

                                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-200 transition">
                                        Terima
                                    </button>
                                </form>

                                {{-- Tombol Tolak (Modal Trigger) --}}
                                <div x-data="{ openReject: false }">
                                    <button @click="openReject = true" type="button" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-red-200 transition">
                                        Tolak
                                    </button>

                                    {{-- Modal Reject --}}
                                    <div x-show="openReject" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
                                        <div class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-sm" @click.away="openReject = false">
                                            <h3 class="font-bold text-slate-800 mb-4 text-lg">Alasan Penolakan</h3>
                                            <form action="{{ route('admin.rentals.update', $rental->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="action" value="cancelled">
                                                <textarea name="rejection_reason" required rows="3" class="w-full border-slate-200 rounded-xl text-sm mb-4 focus:ring-rose-200 focus:border-rose-400" placeholder="Contoh: Bukti buram, nominal kurang..."></textarea>
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" @click="openReject = false" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-50 rounded-lg">Batal</button>
                                                    <button type="submit" class="px-4 py-2 bg-red-500 text-white font-bold rounded-lg hover:bg-red-600">Kirim</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        {{-- 2. FLOW SERAH TERIMA BARANG (Status: APPROVED) --}}
                        @elseif($rental->status == 'approved')
                            <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl mb-4">
                                <h4 class="font-bold text-emerald-700 mb-1">Siap Diambil</h4>
                                <p class="text-xs text-emerald-600">Pembayaran valid. Barang siap diserahkan ke customer.</p>
                            </div>

                            <form action="{{ route('admin.rentals.update', $rental->id) }}" method="POST" onsubmit="return confirm('Serahkan barang ke customer sekarang?')">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="active">
                                <button class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Serahkan Barang (Aktifkan)
                                </button>
                            </form>

                        {{-- 3. FLOW PENGEMBALIAN (Status: ACTIVE / OVERDUE) --}}
                        @elseif($rental->status == 'active' || $rental->status == 'overdue')
                            <a href="{{ route('admin.rentals.return', $rental->id) }}"
                            class="flex items-center justify-center w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-rose-200 transition gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Proses Pengembalian
                            </a>

                        {{-- 4. FLOW PENDING (Belum Bayar) --}}
                        @elseif($rental->status == 'pending')
                            <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl text-center mb-4">
                                <p class="text-sm text-slate-500 font-medium">Menunggu pembayaran customer...</p>
                            </div>

                            <form action="{{ route('admin.rentals.update', $rental->id) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="cancelled">
                                <button class="w-full text-slate-400 hover:text-red-500 font-bold py-2 text-sm transition">
                                    Batalkan Pesanan
                                </button>
                            </form>

                        {{-- 5. STATUS LAINNYA (Completed/Cancelled) --}}
                        @else
                            <button disabled class="w-full bg-slate-100 text-slate-400 font-bold py-4 rounded-xl cursor-not-allowed border border-slate-200">
                                Transaksi {{ $rental->status == 'cancelled' ? 'Dibatalkan' : 'Selesai' }}
                            </button>
                        @endif

                        <x-input-error :messages="$errors->get('status')" class="mt-2"/>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>