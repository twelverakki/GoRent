<x-app-layout>

    <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Kategori</h1>
            <p class="text-slate-400 text-sm mt-1">Atur kategori untuk pengelompokan alat.</p>
        </div>

        <a href="{{ route('admin.categories.create') }}"
           class="flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white px-6 py-3 rounded-2xl font-semibold transition shadow-lg shadow-rose-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Kategori
        </a>
    </div>

    <div class="bg-white rounded-[2rem] p-8 shadow-sm min-h-[500px]" x-data="{ showDeleteModal: false, deleteUrl: '' }">

        <div class="flex justify-between items-center mb-8">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="relative w-full md:w-auto">
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari kategori..."
                    class="pl-10 pr-4 py-2.5 border-none bg-gray-50 rounded-xl text-sm focus:ring-2 focus:ring-rose-200 text-slate-600 w-full md:w-72 transition-all duration-300 placeholder-slate-400">

                <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-sm border-b border-gray-100">
                        <th class="font-medium py-4 pl-4">No</th>
                        <th class="font-medium py-4">Nama Kategori</th>
                        <th class="font-medium py-4">Slug</th>
                        <th class="font-medium py-4">Deskripsi</th>
                        <th class="font-medium py-4 text-right pr-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($categories as $category)
                    <tr class="group hover:bg-gray-50 transition-colors duration-200">

                        <td class="py-4 pl-4 text-slate-500">
                            {{ $loop->iteration }}
                        </td>

                        <td class="py-4">
                            <div class="font-bold text-slate-700">{{ $category->name }}</div>
                        </td>

                        <td class="py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-slate-500 font-mono">
                                {{ $category->slug }}
                            </span>
                        </td>

                        <td class="py-4 text-slate-600">
                            {{ Str::limit($category->description, 60) ?? '-' }}
                        </td>

                        <td class="py-4 text-right pr-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="p-2 text-slate-400 hover:text-indigo-500 hover:bg-indigo-50 rounded-xl transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button
                                    @click="showDeleteModal = true; deleteUrl = '{{ route('admin.categories.destroy', $category->id) }}'"
                                    class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition"
                                    title="Hapus Kategori"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                <p>Belum ada kategori ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Jika kamu menggunakan pagination di controller --}}
        <div class="mt-8">
            {{ $categories->links() }}
        </div>

        <x-delete-modal />
    </div>

</x-app-layout>