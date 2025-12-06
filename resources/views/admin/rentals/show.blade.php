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


                        @if($rental->status == 'pending' || $rental->status == 'paid')
                            <form action="{{ route('admin.rentals.update', $rental->id) }}" method="POST"> @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="active">
                                <button class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-200 transition">
                                    Serahkan Barang (Aktifkan)
                                </button>
                            </form>

                        @elseif($rental->status == 'active' || $rental->status == 'overdue')
                            <a href="{{ route('admin.rentals.return', $rental->id) }}"
                               class="flex items-center justify-center w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-rose-200 transition">
                                Proses Pengembalian
                            </a>

                        @else
                            <button disabled class="w-full bg-slate-100 text-slate-400 font-bold py-4 rounded-xl cursor-not-allowed">
                                Transaksi Selesai
                            </button>
                        @endif

                        <x-input-error :messages="$errors->get('status')" class="mt-2"/>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>