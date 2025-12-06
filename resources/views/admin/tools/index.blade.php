<x-app-layout>

    <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Inventaris Alat</h1>
            <p class="text-slate-400 text-sm mt-1">Kelola stok kamera, lensa, dan peralatan lainnya.</p>
        </div>

        <a href="{{ route('admin.tools.create') }}"
           class="flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white px-6 py-3 rounded-2xl font-semibold transition shadow-lg shadow-rose-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Barang
        </a>
    </div>

    <div class="bg-white rounded-[2rem] p-8 shadow-sm min-h-[500px]" x-data="{ showDeleteModal: false, deleteUrl: '' }">

        <div class="flex justify-between items-center mb-8">
            <form action="{{ route('admin.tools.index') }}" method="GET">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="relative">
                    <select name="category_id" onchange="this.form.submit()"
                            class="appearance-none pl-4 pr-10 py-2.5 rounded-xl bg-white ring-1 border border-rose-200 ring-rose-200 text-slate-600 text-sm font-medium focus:ring-2 focus:ring-rose-200 focus:border-rose-300 cursor-pointer shadow-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="absolute right-3 top-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </form>

            <form action="{{ route('admin.tools.index') }}" method="GET" class="relative">

                @if(request('category_id'))
                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                @endif

                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari alat..."
                    class="pl-10 pr-4 py-2.5 border-none bg-gray-50 rounded-xl text-sm focus:ring-2 focus:ring-rose-200 text-slate-600 w-full md:w-64 focus:w-full md:focus:w-72 transition-all duration-300 placeholder-slate-400">

                <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>

            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-sm border-b border-gray-100">
                        <th class="font-medium py-4 pl-4">Produk</th>
                        <th class="font-medium py-4">Kategori</th>
                        <th class="font-medium py-4">Harga / Hari</th>
                        <th class="font-medium py-4">Stok</th>
                        <th class="font-medium py-4">Status</th>
                        <th class="font-medium py-4 text-right pr-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($tools as $tool)
                    <tr class="group hover:bg-gray-50 transition-colors duration-200">

                        <td class="py-4 pl-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 overflow-hidden">
                                    @if($tool->image)
                                        <img src="{{ Storage::url($tool->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No IMG</div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-slate-700">{{ $tool->name }}</div>
                                    <div class="text-xs text-slate-400">ID: {{ substr($tool->id, 0, 8) }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-500">
                                {{ $tool->category->name }}
                            </span>
                        </td>

                        <td class="py-4 font-semibold text-slate-600">
                            Rp {{ number_format($tool->price_per_day, 0, ',', '.') }}
                        </td>

                        <td class="py-4">
                            <span class="text-slate-600 font-medium">{{ $tool->stock }} Unit</span>
                        </td>

                        <td class="py-4">
                            @if($tool->stock > 0)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Ready
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Habis
                                </span>
                            @endif
                        </td>

                        <td class="py-4 text-right pr-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.tools.edit', $tool->id) }}" class="p-2 text-slate-400 hover:text-indigo-500 hover:bg-indigo-50 rounded-xl transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button
                                    @click="showDeleteModal = true; deleteUrl = '{{ route('admin.tools.destroy', $tool->id) }}'"
                                    class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition"
                                    title="Hapus Alat"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-slate-400">
                            Belum ada alat. Silakan tambah data baru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $tools->links() }}
        </div>

        <x-delete-modal />
    </div>

</x-app-layout>