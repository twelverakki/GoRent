<x-frontend-layout>
    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            <nav class="flex items-center text-sm text-slate-500 pt-8 mb-8 space-x-2">
                <a href="{{ route('home') }}" class="hover:text-rose-500 transition">Home</a><span class="text-slate-300">/</span>
                <a href="{{ route('public.tool.index') }}" class="hover:text-rose-500 transition">Katalog</a><span class="text-slate-300">/</span>
                <span class="text-slate-900 font-semibold truncate max-w-[200px]">{{ $tool->name }}</span>
            </nav>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-20 items-start">
                <div class="relative group">
                    <div class="relative aspect-square rounded-[2.5rem] bg-slate-50 border border-slate-100 overflow-hidden flex items-center justify-center shadow-lg">

                        @if($tool->image)
                            <img src="{{ Storage::url($tool->image) }}"
                                 class="w-full h-full object-cover aspect-square transform group-hover:scale-110 transition duration-700 ease-out"
                                 alt="{{ $tool->name }}">
                        @else
                            <div class="flex flex-col items-center text-slate-300">
                                <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="font-bold">No Image</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col">

                    <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight mb-4">
                        {{ $tool->name }}
                    </h1>

                    <div class="flex items-center gap-4 mb-8">
                        @if($tool->stock > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700">
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                                Stok Tersedia: {{ $tool->stock }} Unit
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-700">
                                <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                                Stok Habis
                            </span>
                        @endif

                        <span class="text-sm text-slate-400">ID: #{{ substr($tool->id, 0, 8) }}</span>
                    </div>

                    <div class="mb-10">
                        <p class="text-sm text-slate-500 font-medium mb-1">Harga Sewa Harian</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-5xl font-black text-rose-500 tracking-tight">
                                Rp {{ number_format($tool->price_per_day, 0, ',', '.') }}
                            </span>
                            <span class="text-xl text-slate-400 font-medium">/ hari</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-10">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-xs text-slate-400 font-bold uppercase">Kategori</p>
                            <p class="font-bold text-slate-800">{{ $tool->category->name }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-xs text-slate-400 font-bold uppercase mb-1">Kondisi Fisik</p>

                            <p class="font-bold text-slate-800 text-lg flex items-center gap-2">
                                {{ $tool->condition }}

                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </p>
                        </div>
                    </div>

                    <div class="mb-10">
                        <h3 class="text-lg font-bold text-slate-900 mb-3">Deskripsi & Kelengkapan</h3>
                        <div class="prose prose-slate text-slate-500 leading-relaxed">
                            <p>{{ $tool->description ?? 'Tidak ada deskripsi detail untuk alat ini.' }}</p>
                        </div>
                    </div>

                    <div class="mt-auto pt-6 border-t border-slate-100 flex flex-col sm:flex-row gap-4">

                        @auth
                            @if($tool->stock > 0)
                                <a href="{{ route('cart.add', $tool->id) }}"
                                    class="w-full h-10 font-bold rounded-full bg-slate-900 text-white flex items-center justify-center hover:bg-rose-500 transition shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add To Cart
                                </a>
                            @else
                                <button disabled class="flex-1 bg-slate-100 text-slate-400 px-8 py-4 rounded-xl font-bold cursor-not-allowed border border-slate-200">
                                    Stok Sedang Kosong
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                               class="flex-1 bg-rose-500 text-white px-8 py-4 rounded-xl font-bold text-center hover:bg-rose-600 transition shadow-xl shadow-rose-200">
                               Login untuk Booking
                            </a>
                        @endauth

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-frontend-layout>