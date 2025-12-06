<x-app-layout>

    {{-- HEADER STYLING (Mengikuti Inventaris Alat) --}}
    <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Pengguna</h1>
            <p class="text-slate-400 text-sm mt-1">Kelola daftar Admin dan Customer yang terdaftar di aplikasi.</p>
        </div>

        {{-- Jika nanti kamu memutuskan untuk mengaktifkan CREATE lagi,
             un-comment kode ini:
        <a href="{{ route('admin.users.create') }}"
           class="flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white px-6 py-3 rounded-2xl font-semibold transition shadow-lg shadow-rose-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah User
        </a>
        --}}
    </div>

    {{-- KONTEN UTAMA (Mengikuti Inventaris Alat) --}}
    <div class="bg-white rounded-[2rem] p-8 shadow-sm min-h-[500px]" x-data="{ showDeleteModal: false, deleteUrl: '' }">

        {{-- Notifikasi Sukses/Error (Dipertahankan) --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6 font-medium border border-green-200 shadow-md">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6 font-medium border border-red-200 shadow-md">{{ session('error') }}</div>
        @endif

        {{-- BAGIAN SEARCH (Disederhanakan dari Template Tools) --}}
        <div class="flex justify-end items-center mb-8">
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative">
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari user (nama/email)..."
                    class="pl-10 pr-4 py-2.5 border-none bg-gray-50 rounded-xl text-sm focus:ring-2 focus:ring-rose-200 text-slate-600 w-full md:w-64 focus:w-full md:focus:w-72 transition-all duration-300 placeholder-slate-400">

                <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>


        {{-- Tabel Daftar User (Disesuaikan Stylingnya) --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-sm border-b border-gray-100">
                        <th class="font-medium py-4 pl-4">NAMA & ID</th>
                        <th class="font-medium py-4">EMAIL</th>
                        <th class="font-medium py-4">ROLE</th>
                        <th class="font-medium py-4 text-right pr-4">AKSI</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse ($users as $user)
                        <tr class="group hover:bg-gray-50 transition-colors duration-200">
                            {{-- Nama & ID --}}
                            <td class="py-4 pl-4">
                                <div class="font-bold text-slate-700">{{ $user->name }}</div>
                                <div class="text-xs text-slate-400">ID: {{ substr($user->id, 0, 8) }}</div>
                            </td>

                            {{-- Email --}}
                            <td class="py-4">
                                <span class="text-slate-600 font-medium">{{ $user->email }}</span>
                            </td>

                            {{-- Role --}}
                            <td class="py-4">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Customer
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-4 text-right pr-4">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Edit Button (Menggunakan Icon Styling Tools) --}}
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       class="p-2 text-slate-400 hover:text-indigo-500 hover:bg-indigo-50 rounded-xl transition"
                                       title="Edit User">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    {{-- Delete Form (Menggunakan Icon Styling Tools) --}}
                                    <button
                                        @click="showDeleteModal = true; deleteUrl = '{{ route('admin.users.destroy', $user->id) }}'"
                                        class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition"
                                        title="Hapus User"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-10 text-slate-400">
                                Belum ada data pengguna yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $users->links() }}
        </div>

        {{-- Asumsi kamu memiliki komponen delete-modal yang terintegrasi dengan Alpine.js (x-data) --}}
        <x-delete-modal />
    </div>

</x-app-layout>