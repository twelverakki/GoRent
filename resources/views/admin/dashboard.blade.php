<x-app-layout>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Dashboard Overview</h1>
        <p class="text-slate-400 text-sm mt-1">Selamat datang kembali, {{ Auth::user()->name }} 👋</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">

        <div class="bg-white p-6 rounded-[2rem] shadow-sm flex flex-col justify-between h-40">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-rose-50 rounded-2xl text-rose-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-slate-800">Rp {{ number_format($totalRevenue) }}</h3>
                <p class="text-slate-400 text-sm font-medium">Total Revenue</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm flex flex-col justify-between h-40">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-purple-50 rounded-2xl text-purple-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                    </svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $totalCustomers }}</h3>
                <p class="text-slate-400 text-sm font-medium">Pelanggan</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm flex flex-col justify-between h-40">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-blue-50 rounded-2xl text-blue-500">
                    {{-- <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5" viewBox="0 0 16 16"><path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2zm3.564 1.426L5.596 5 8 5.961 14.154 3.5zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z"/></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $totalTools }}</h3>
                <p class="text-slate-400 text-sm font-medium">Total Alat Tersedia</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm flex flex-col justify-between h-40 border-2 border-dashed border-gray-100">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-orange-50 rounded-2xl text-orange-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $totalRentActive }}</h3>
                <p class="text-slate-400 text-sm font-medium">Sedang Disewa</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h2>
            <a href="{{ route('admin.rentals.index') }}" class="text-sm font-semibold text-rose-500 hover:text-rose-600">Lihat Semua</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <tbody class="text-sm">
                    @forelse($recentRentals as $rental)

                    <tr onclick="window.location='{{ route('admin.rentals.show', $rental->id) }}'"
                        class="group hover:bg-rose-50/50 cursor-pointer transition border-b border-gray-50 last:border-none">

                        <td class="py-4 pl-2">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ $rental->user->name }}&background=random&color=fff&size=32" class="w-8 h-8 rounded-full">
                                <div>
                                    <p class="font-bold text-slate-700 group-hover:text-rose-600 transition">{{ $rental->user->name }}</p>
                                    <p class="text-xs text-slate-400 font-mono">
                                        {{ $rental->invoice_no }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="py-4 text-slate-600 font-medium">
                            {{ $rental->items->first()->tool->name ?? 'Item dihapus' }}
                            @if($rental->items->count() > 1)
                                <span class="text-xs text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded ml-1">+{{ $rental->items->count() - 1 }}</span>
                            @endif
                        </td>

                        <td class="py-4">
                            @php
                                $statusConfig = [
                                    'pending' => ['bg-yellow-50', 'text-yellow-600'],
                                    'paid' => ['bg-blue-50', 'text-blue-600'],
                                    'active' => ['bg-emerald-50', 'text-emerald-600'],
                                    'completed' => ['bg-gray-100', 'text-gray-600'],
                                    'overdue' => ['bg-red-50', 'text-red-600'],
                                    'cancelled' => ['bg-red-50', 'text-red-600'],
                                ];
                                $style = $statusConfig[$rental->status] ?? ['bg-gray-50', 'text-gray-500'];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $style[0] }} {{ $style[1] }}">
                                {{ ucfirst($rental->status) }}
                            </span>
                        </td>

                        <td class="py-4 text-right pr-2">
                            <div class="flex flex-col items-end justify-center">
                                <span class="text-xs text-slate-400 mb-1">{{ $rental->created_at->diffForHumans() }}</span>

                                <span class="text-rose-400 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="text-center py-10 border-2 border-dashed border-gray-100 rounded-3xl bg-gray-50">
                                <p class="text-gray-400 text-sm">Belum ada aktivitas penyewaan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- <div class="bg-white rounded-[2.5rem] p-8 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h2>
            <a href="{{ route('admin.rentals.index') }}" class="text-sm font-semibold text-rose-500 hover:text-rose-600">Lihat Semua</a>
        </div>

        <div class="text-center py-10 border-2 border-dashed border-gray-100 rounded-3xl bg-gray-50">
            <p class="text-gray-400 text-sm">Belum ada aktivitas baru hari ini.</p>
        </div>
    </div> --}}

</x-app-layout>