<x-app-layout>
    <div class="max-w-3xl mx-auto">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.tools.index') }}" class="p-3 bg-white rounded-2xl shadow-sm text-slate-400 hover:text-rose-500 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Edit Alat</h1>
                <p class="text-slate-400 text-sm">Update data: {{ $tool->name }}</p>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-50">

            <form action="{{ route('admin.tools.update', $tool->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Nama Alat</label>
                    <input type="text" name="name" value="{{ $tool->name }}" required
                        class="w-full px-5 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-medium placeholder-slate-400">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-bold text-slate-600 mb-2">Kategori</label>
                        <select name="category_id" required class="w-full px-5 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-medium cursor-pointer">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $tool->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-600 mb-2">Stok Saat Ini</label>
                        <input type="number" name="stock" value="{{ $tool->stock }}" required
                            class="w-full px-5 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Harga Sewa (Per Hari)</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>
                        <input type="number" name="price_per_day" value="{{ $tool->price_per_day }}" required
                            class="w-full pl-12 pr-5 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-bold text-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Foto Produk</label>

                    @if($tool->image)
                        <div class="flex items-center gap-4 mb-4 p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <img src="{{ Storage::url($tool->image) }}" class="w-16 h-16 object-cover rounded-lg">
                            <div>
                                <p class="text-xs text-slate-500 font-semibold">Gambar Saat Ini</p>
                                <p class="text-[10px] text-slate-400">Upload baru untuk mengganti</p>
                            </div>
                        </div>
                    @endif

                    <input type="file" name="image"
                        class="block w-full text-sm text-slate-500
                        file:mr-4 file:py-3 file:px-6
                        file:rounded-l-xl file:border-0
                        file:text-sm file:font-bold
                        file:bg-rose-50 file:text-rose-500
                        hover:file:bg-rose-100
                        cursor-pointer bg-slate-50 border border-slate-100 rounded-xl shadow-sm transition">
                    <p class="text-xs text-slate-400 mt-2">*Biarkan kosong jika tidak ingin mengubah gambar.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Deskripsi Lengkap</label>
                    <textarea name="description" rows="4"
                        class="w-full px-5 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-medium">{{ $tool->description }}</textarea>
                </div>

                <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div class="flex items-center h-5">
                        <input type="hidden" name="is_available" value="0">
                        <input type="checkbox" name="is_available" value="1" id="status_check" {{ $tool->is_available ? 'checked' : '' }}
                            class="w-5 h-5 text-rose-500 border-gray-300 rounded focus:ring-rose-500 cursor-pointer">
                    </div>
                    <div class="ml-2 text-sm">
                        <label for="status_check" class="font-bold text-slate-700 cursor-pointer">Tampilkan di Katalog?</label>
                        <p class="text-xs text-slate-500">Jika dimatikan, customer tidak bisa melihat alat ini.</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('admin.tools.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-rose-100 transition transform active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>