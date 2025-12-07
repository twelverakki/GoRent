<x-app-layout>
    <div class="max-w-4xl mx-auto pb-12">

        {{-- Header Section --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 flex items-center justify-center text-rose-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Pengaturan Profil</h1>
                <p class="text-slate-400 text-sm mt-1">Kelola informasi akun dan keamananmu.</p>
            </div>
        </div>

        <div class="space-y-8">

            {{-- 1. Card Informasi Profil --}}
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-50 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-rose-50 rounded-bl-[4rem] -mr-8 -mt-8 z-0"></div>

                <h2 class="text-lg font-bold text-slate-800 mb-6 relative z-10">Informasi Pribadi</h2>

                {{-- Form Verifikasi Email (Invisible form helper) --}}
                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <div class="flex flex-col md:flex-row gap-8 relative z-10">

                    {{-- Avatar Section --}}
                    <div class="flex flex-col items-center gap-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=F43F5E&color=fff&size=256"
                             class="w-32 h-32 rounded-3xl object-cover shadow-lg shadow-rose-100 ring-4 ring-white">
                        <div class="text-center">
                            <p class="font-bold text-slate-700">{{ $user->name }}</p>
                            <p class="text-xs text-slate-400">Customer</p>
                        </div>
                    </div>

                    {{-- Form Update Profil --}}
                    <form method="post" action="{{ route('profile.update') }}" class="flex-1 space-y-5">
                        @csrf
                        @method('patch')

                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-600 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-5 py-3 bg-slate-50 ring-1 ring-rose-200 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-medium placeholder-slate-400 transition">
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        {{-- Nomor Telepon (TAMBAHAN PENTING) --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-600 mb-2">Nomor Telepon (WhatsApp)</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full px-5 py-3 bg-slate-50 ring-1 ring-rose-200 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-medium placeholder-slate-400 transition"
                                placeholder="08xxxxxxxxxx">
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>

                        {{-- Alamat (TAMBAHAN PENTING) --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-600 mb-2">Alamat Lengkap</label>
                            <textarea name="address" rows="2"
                                class="w-full px-5 py-3 bg-slate-50 ring-1 ring-rose-200 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-medium placeholder-slate-400 transition"
                                placeholder="Jalan, RT/RW, Kelurahan...">{{ old('address', $user->address) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-600 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-5 py-3 bg-slate-50 ring-1 ring-rose-200 border-none rounded-xl focus:ring-2 focus:ring-rose-200 text-slate-700 font-medium placeholder-slate-400 transition">
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />

                            {{-- Logika Verifikasi Email --}}
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-3 p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                                    <p class="text-sm text-yellow-800 font-medium">
                                        {{ __('Email anda belum diverifikasi.') }}
                                    </p>
                                    <button form="send-verification" class="mt-2 text-sm underline text-yellow-600 hover:text-yellow-900 font-bold">
                                        {{ __('Klik di sini untuk kirim ulang email verifikasi.') }}
                                    </button>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 font-medium text-sm text-emerald-600">
                                            {{ __('Link verifikasi baru telah dikirim ke email anda.') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-rose-100 transition transform active:scale-95">
                                Simpan Perubahan
                            </button>

                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-500 font-bold flex items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Data Tersimpan!
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- 2. Card Update Password --}}
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-50">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800">Ganti Password</h2>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    @method('put')

                    <div>
                        <label class="block text-sm font-bold text-slate-600 mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" autocomplete="current-password"
                            class="w-full px-5 py-3 bg-slate-50 ring-1 ring-slate-200 border-none rounded-xl focus:ring-2 focus:ring-indigo-200 text-slate-700 transition">
                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-600 mb-2">Password Baru</label>
                            <input type="password" name="password" autocomplete="new-password"
                                class="w-full px-5 py-3 bg-slate-50 ring-1 ring-slate-200 border-none rounded-xl focus:ring-2 focus:ring-indigo-200 text-slate-700 transition">
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-600 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" autocomplete="new-password"
                                class="w-full px-5 py-3 bg-slate-50 ring-1 ring-slate-200 border-none rounded-xl focus:ring-2 focus:ring-indigo-200 text-slate-700 transition">
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-slate-200 transition transform active:scale-95">
                            Update Password
                        </button>

                        @if (session('status') === 'password-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-500 font-bold flex items-center gap-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Password Diperbarui!
                            </p>
                        @endif
                    </div>
                </form>
            </div>

            {{-- 3. Danger Zone (Hapus Akun) --}}
            <div class="bg-rose-50 rounded-[2rem] p-8 border border-rose-100 opacity-90 hover:opacity-100 transition">
                <div class="flex justify-between items-start md:items-center flex-col md:flex-row gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-rose-700">Hapus Akun</h2>
                        <p class="text-rose-600/80 text-sm mt-1">
                            Setelah akun dihapus, semua data akan hilang permanen. Harap pertimbangkan kembali.
                        </p>
                    </div>

                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                        class="bg-white text-rose-600 border border-rose-200 hover:bg-rose-600 hover:text-white px-6 py-2.5 rounded-xl font-bold transition">
                        Hapus Akun Saya
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Akun --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-slate-900">
                {{ __('Apakah anda yakin ingin menghapus akun?') }}
            </h2>

            <p class="mt-1 text-sm text-slate-600">
                {{ __('Setelah akun dihapus, semua sumber daya dan data akan dihapus secara permanen. Silakan masukkan password anda untuk konfirmasi.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <input type="password" name="password" placeholder="Masukkan password anda"
                    class="w-full px-5 py-3 bg-slate-50 ring-1 ring-slate-200 border-none rounded-xl focus:ring-2 focus:ring-rose-500 text-slate-700">

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition">
                    {{ __('Batal') }}
                </button>

                <button type="submit" class="px-4 py-2 bg-rose-600 text-white rounded-xl hover:bg-rose-700 shadow-lg shadow-rose-200 transition">
                    {{ __('Ya, Hapus Akun') }}
                </button>
            </div>
        </form>
    </x-modal>

</x-app-layout>