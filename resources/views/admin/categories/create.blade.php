<x-app-layout>
    <div class="max-w-3xl mx-auto">

        {{-- Header & Back Button --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.categories.index') }}" class="p-3 bg-white rounded-2xl shadow-sm text-slate-400 hover:text-rose-500 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Tambah Kategori</h1>
                <p class="text-slate-400 text-sm mt-1">Buat kategori baru untuk pengelompokan alat.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-50">

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Nama Kategori</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                        class="w-full px-5 py-3 bg-slate-50 ring-1 ring-rose-200 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-medium placeholder-slate-400"
                        placeholder="Misal: Kamera Mirrorless">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="4"
                        class="w-full px-5 py-3 bg-slate-50 ring-1 ring-rose-200 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-medium placeholder-slate-400"
                        placeholder="Tulis deskripsi singkat tentang kategori ini...">{{ old('description') }}</textarea>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-rose-100 transition transform active:scale-95">
                        Simpan Kategori
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>