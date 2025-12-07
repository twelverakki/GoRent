<x-frontend-layout>
    <div class="py-12 pt-20 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-slate-800">Riwayat Sewa</h1>
                <a href="{{ route('public.tool.index') }}" class="text-sm font-bold text-rose-500 hover:text-rose-600">
                    + Sewa Lagi
                </a>
            </div>

            <div class="space-y-6">
                @forelse($rentals as $rental)
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 overflow-hidden">

                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-slate-50 pb-4 mb-4 gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-sm text-slate-400">#{{ $rental->invoice_no }}</span>
                                <span class="text-xs text-slate-300">•</span>
                                <span class="text-sm font-bold text-slate-700">{{ $rental->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">
                                Durasi: {{ $rental->start_date->format('d M') }} - {{ $rental->end_date->format('d M Y') }}
                                ({{ $rental->start_date->diffInDays($rental->end_date) }} Hari)
                            </p>
                        </div>

                        <x-status-badge :status="$rental->status" />
                    </div>

                    <div class="space-y-4">
                        @foreach($rental->items as $item)
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-slate-50 rounded-xl flex-shrink-0 overflow-hidden border border-slate-100">
                                @if($item->tool->image)
                                    <img src="{{ Storage::url($item->tool->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xs text-slate-300">No IMG</div>
                                @endif
                            </div>

                            <div class="flex-1">
                                <h4 class="font-bold text-slate-800 text-sm line-clamp-1">{{ $item->tool->name }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $item->quantity }} unit x Rp {{ number_format($item->price_snapshot) }}
                                </p>
                            </div>

                            <div class="text-sm font-bold text-slate-600">
                                Rp {{ number_format($item->subtotal) }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-4 border-t border-dashed border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">

                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-500">Total Tagihan:</span>
                            <span class="text-xl font-black text-rose-500">Rp {{ number_format($rental->total_price) }}</span>
                        </div>

                        <div>
                            @if($rental->status == 'pending')
                                {{-- Tombol ke Halaman Upload Bukti Bayar --}}
                                <a href="{{ route('rentals.payment', $rental->id) }}"
                                class="inline-flex items-center px-6 py-2.5 bg-rose-500 text-white rounded-xl text-sm font-bold hover:bg-rose-600 transition shadow-lg shadow-rose-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    Konfirmasi Bayar
                                </a>

                            @elseif($rental->status == 'paid')
                                {{-- Status Menunggu Verifikasi --}}
                                <span class="inline-flex items-center px-4 py-2 bg-orange-50 text-orange-600 rounded-xl text-sm font-bold border border-orange-100 cursor-default">
                                    <svg class="w-4 h-4 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Menunggu Verifikasi
                                </span>

                            @elseif($rental->status == 'approved' || $rental->status == 'active')
                                <span class="text-sm font-bold text-emerald-600 bg-emerald-50 px-4 py-2 rounded-lg border border-emerald-100">
                                    Sedang Disewa
                                </span>

                            @elseif($rental->status == 'completed')
                                <a href="{{ route('rentals.create') }}" class="text-sm font-bold text-slate-500 hover:text-rose-500 underline">
                                    Sewa Lagi
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
                @empty
                <div class="text-center py-20">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Belum ada riwayat sewa</h3>
                    <p class="text-slate-500 mt-2 mb-8">Yuk, mulai sewa perlengkapan pertamamu!</p>
                    <a href="{{ route('public.tool.index') }}" class="px-8 py-3 bg-rose-500 text-white rounded-full font-bold shadow-lg hover:bg-rose-600 transition">
                        Lihat Katalog
                    </a>
                </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $rentals->links() }}
            </div>

        </div>
    </div>
</x-frontend-layout>