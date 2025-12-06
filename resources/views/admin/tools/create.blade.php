<x-app-layout>
    <div class="max-w-3xl mx-auto">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.tools.index') }}" class="p-3 bg-white rounded-2xl shadow-sm text-slate-400 hover:text-rose-500 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Tambah Alat</h1>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-50">

            <form action="{{ route('admin.tools.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Nama Alat</label>
                    <input type="text" name="name" required
                        class="w-full px-5 py-3 bg-slate-50 ring-1 ring-rose-200 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-medium placeholder-slate-400"
                        placeholder="Misal: Sony Alpha A7 III">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-bold text-slate-600 mb-2">Kategori</label>
                        <select name="category_id" required class="w-full ring-1 ring-rose-200 px-5 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-medium cursor-pointer">
                            <option value="" disabled selected>Pilih Kategori...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-600 mb-2">Stok Awal</label>
                        <input type="number" name="stock" required
                            class="w-full px-5 py-3 bg-slate-50 border-none ring-1 ring-rose-200 rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-medium"
                            placeholder="0">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Harga Sewa (Rp/Hari)</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>

                        <input type="number" name="price_per_day" required
                            class="w-full pl-14 pr-5 py-3 bg-slate-50 ring-1 ring-rose-200 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-bold text-lg placeholder-slate-300"
                            placeholder="0">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Denda Keterlambatan (Per Hari)</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>
                        <input type="number" name="late_fee_per_day"
                            class="w-full pl-14 pr-5 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-bold text-lg placeholder-slate-300"
                            placeholder="0">
                    </div>
                    <p class="text-xs text-slate-400 mt-2">Jika kosong, sistem menggunakan 50% dari harga sewa.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Foto Produk</label>

                    <div class="relative w-full">
                        <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-slate-200 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-rose-50 hover:border-rose-300 transition duration-300 group overflow-hidden relative">

                            <div id="placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-slate-400 group-hover:text-rose-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="mb-1 text-sm text-slate-500 font-medium group-hover:text-rose-600">Klik untuk pilih gambar</p>
                                <p class="text-xs text-slate-400">JPG, PNG (Max 2MB)</p>
                            </div>

                            <img id="img-preview" class="hidden w-full h-full object-cover absolute inset-0">

                            <input type="file" name="image" class="hidden" accept="image/*" onchange="previewImage(this)" />
                        </label>
                    </div>

                    <script>
                        function previewImage(input) {
                            const placeholder = document.getElementById('placeholder');
                            const preview = document.getElementById('img-preview');

                            if (input.files && input.files[0]) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    preview.src = e.target.result;
                                    preview.classList.remove('hidden'); // Munculkan gambar
                                    placeholder.classList.add('hidden'); // Sembunyikan ikon awan
                                }
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
                    </script>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Deskripsi Lengkap</label>
                    <textarea name="description" rows="4"
                        class="w-full px-5 py-3 bg-slate-50 ring-1 ring-rose-200 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-medium placeholder-slate-400"
                        placeholder="Tulis kelengkapan dan kondisi alat..."></textarea>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-rose-100 transition transform active:scale-95">
                        Simpan Data
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>