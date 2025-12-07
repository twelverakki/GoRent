@if (false)
<x-app-layout>

    <div class="flex justify-between items-end mb-10">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Transaksi Sewa</h1>
            <p class="text-slate-400 text-sm mt-1">Pantau semua aktivitas penyewaan barang masuk dan keluar.</p>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] p-8 shadow-sm min-h-[500px] border border-slate-50">

        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">

            <div class="flex overflow-x-auto pb-2 md:pb-0 gap-2 w-full md:w-auto no-scrollbar">
                @foreach($statuses as $key => $label)
                    <a href="{{ route('admin.rentals.index', ['status' => $key]) }}"
                       class="px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition border
                       {{ request('status') == $key
                          ? 'bg-rose-500 text-white border-rose-500 shadow-lg shadow-rose-200'
                          : 'bg-white text-slate-500 border-slate-100 hover:bg-slate-50 hover:border-slate-200' }}">
                       {{ $label }}
                    </a>
                @endforeach
            </div>

            <form method="GET" class="relative w-full md:w-72">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari Invoice / Nama..."
                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-rose-200 text-slate-700 placeholder-slate-400 transition-all">

                <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider border-b border-slate-100">
                        <th class="font-bold py-4 pl-4">Invoice</th>
                        <th class="font-bold py-4">Penyewa</th>
                        <th class="font-bold py-4">Barang</th>
                        <th class="font-bold py-4">Jadwal</th>
                        <th class="font-bold py-4">Total</th>
                        <th class="font-bold py-4">Status</th>
                        <th class="font-bold py-4 text-right pr-4">Detail</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($rentals as $rental)

                    <tr onclick="window.location='{{ route('admin.rentals.show', $rental->id) }}'"
                        class="group hover:bg-rose-50/40 cursor-pointer transition border-b border-slate-50 last:border-none">

                        <td class="py-4 pl-4 font-mono font-bold text-slate-600">
                            #{{ substr($rental->invoice_no, -5) }}
                        </td>

                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ $rental->user->name }}&background=random&color=fff&size=32" class="w-8 h-8 rounded-full border border-white shadow-sm">
                                <div>
                                    <p class="font-bold text-slate-700 group-hover:text-rose-600 transition">{{ $rental->user->name }}</p>
                                    <p class="text-[10px] text-slate-400">Customer</p>
                                </div>
                            </div>
                        </td>

                        <td class="py-4 text-slate-600">
                            <span class="font-medium">{{ $rental->items->first()->tool->name ?? 'Item Terhapus' }}</span>
                            @if($rental->items->count() > 1)
                                <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded ml-1 border border-slate-200">
                                    +{{ $rental->items->count() - 1 }}
                                </span>
                            @endif
                        </td>

                        <td class="py-4">
                            <div class="flex flex-col">
                                <span class="text-slate-600 font-medium">{{ $rental->end_date->format('d M, Y') }}</span>
                                <span class="text-xs text-slate-400">{{ $rental->end_date->diffForHumans() }}</span>
                            </div>
                        </td>

                        <td class="py-4 font-bold text-slate-700">
                            Rp {{ number_format($rental->total_price, 0, ',', '.') }}
                        </td>

                        <td class="py-4">
                            <x-status-badge :status="$rental->status" />
                        </td>

                        <td class="py-4 text-center pr-4 ">
                            <div class="w-8 h-8 ml-auto rounded-full bg-white border border-slate-100 flex items-center justify-center group-hover:bg-rose-500 group-hover:border-rose-500 transition shadow-sm">
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-20">
                            <div class="inline-block p-4 rounded-full bg-slate-50 text-slate-400 mb-3">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <p class="text-slate-500 font-medium text-lg">Tidak ada data ditemukan.</p>
                            <p class="text-slate-400 text-sm">Coba ubah filter atau kata kunci pencarian.</p>

                            @if(request('status') || request('search'))
                                <a href="{{ route('admin.rentals.index') }}" class="inline-block mt-4 text-rose-500 font-bold hover:underline">
                                    Reset Filter
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8 border-t border-slate-50 pt-6">
            {{ $rentals->withQueryString()->links() }}
        </div>
    </div>

</x-app-layout>
@else

<x-app-layout>

    <div class="flex justify-between items-end mb-10">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Transaksi Sewa</h1>
            <p class="text-slate-400 text-sm mt-1">Pantau semua aktivitas penyewaan barang masuk dan keluar.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] p-8 shadow-sm min-h-[500px] border border-slate-50">

        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div class="flex overflow-x-auto pb-2 md:pb-0 gap-2 w-full md:w-auto no-scrollbar">
                @foreach($statuses as $key => $label)
                    <a href="{{ route('admin.rentals.index', ['status' => $key]) }}"
                       class="px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition border
                       {{ request('status') == $key
                          ? 'bg-rose-500 text-white border-rose-500 shadow-lg shadow-rose-200'
                          : 'bg-white text-slate-500 border-slate-100 hover:bg-slate-50 hover:border-slate-200' }}">
                       {{ $label }}
                    </a>
                @endforeach
            </div>

            <form method="GET" class="relative w-full md:w-72">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari Invoice / Nama..."
                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-rose-200 text-slate-700 placeholder-slate-400 transition-all">

                <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider border-b border-slate-100">
                        <th class="font-bold py-4 pl-4">Invoice</th>
                        <th class="font-bold py-4">Penyewa</th>
                        <th class="font-bold py-4">Bukti Bayar</th>
                        <th class="font-bold py-4">Status</th>
                        <th class="font-bold py-4">Aksi</th>
                        <th class="font-bold py-4 text-right pr-4">Detail</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($rentals as $rental)

                    <tr class="group hover:bg-rose-50/40 transition border-b border-slate-50 last:border-none">

                        <td class="py-4 pl-4 font-mono font-bold text-slate-600">
                            #{{ substr($rental->invoice_no, -5) }}
                        </td>

                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ $rental->user->name }}&background=random&color=fff&size=32" class="w-8 h-8 rounded-full border border-white shadow-sm">
                                <div>
                                    <p class="font-bold text-slate-700">{{ $rental->user->name }}</p>
                                    <p class="text-[10px] text-slate-400">Customer</p>
                                </div>
                            </div>
                        </td>

                        <td class="py-4">
                            @if($rental->payment_proof)
                                <a href="{{ Storage::url($rental->payment_proof) }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-xs font-bold underline">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Lihat Foto
                                </a>
                            @else
                                <span class="text-xs text-slate-400">-</span>
                            @endif
                        </td>

                        <td class="py-4">
                            <x-status-badge :status="$rental->status" />
                        </td>

                        <td class="py-4">
                            @if($rental->status == 'paid')
                                <div class="flex flex-col gap-2 w-24">
                                    {{-- FORM APPROVE --}}
                                    <form action="{{ route('admin.rentals.update', $rental->id) }}" method="POST" onsubmit="return confirm('Terima pembayaran ini?')">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white text-xs px-2 py-1.5 rounded-lg font-bold shadow-sm transition">
                                            Terima
                                        </button>
                                    </form>

                                    {{-- TOMBOL REJECT (Modal) --}}
                                    <div x-data="{ open: false }">
                                        <button @click="open = true" type="button" class="w-full bg-red-500 hover:bg-red-600 text-white text-xs px-2 py-1.5 rounded-lg font-bold shadow-sm transition">
                                            Tolak
                                        </button>

                                        {{-- MODAL REJECT --}}
                                        <div x-show="open" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
                                            <div class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-sm" @click.away="open = false">
                                                <h3 class="font-bold text-slate-800 mb-4 text-lg">Alasan Penolakan</h3>

                                                <form action="{{ route('admin.rentals.update', $rental->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="action" value="reject">

                                                    <textarea name="rejection_reason" required rows="3"
                                                        class="w-full border-slate-200 rounded-xl text-sm mb-4 focus:ring-rose-200 focus:border-rose-400"
                                                        placeholder="Contoh: Foto buram / Nominal salah"></textarea>

                                                    <div class="flex justify-end gap-2">
                                                        <button type="button" @click="open = false" class="text-slate-500 text-sm font-bold px-4 py-2 hover:bg-slate-50 rounded-lg">Batal</button>
                                                        <button type="submit" class="bg-red-500 text-white text-sm font-bold px-4 py-2 rounded-lg hover:bg-red-600 shadow-lg shadow-red-200">Kirim</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-[10px] text-slate-300 uppercase font-bold">No Action</span>
                            @endif
                        </td>

                        <td class="py-4 text-center pr-4">
                            <a href="{{ route('admin.rentals.show', $rental->id) }}" class="w-8 h-8 ml-auto rounded-full bg-white border border-slate-100 flex items-center justify-center hover:bg-rose-500 hover:border-rose-500 transition shadow-sm group-hover/row:shadow-md">
                                <svg class="w-4 h-4 text-slate-400 hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-20">
                            <p class="text-slate-500 font-medium text-lg">Tidak ada data ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8 border-t border-slate-50 pt-6">
            {{ $rentals->withQueryString()->links() }}
        </div>
    </div>

</x-app-layout>

@endif