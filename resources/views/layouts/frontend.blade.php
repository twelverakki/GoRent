<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GoRent - Sewa Kamera Profesional</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="antialiased relative bg-white text-slate-800">

    <nav class="sticky top-0 left-0 w-full z-50 backdrop-blur">
        <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
            <a href="/" class="text-2xl font-black tracking-tighter text-slate-900">
                Go<span class="text-rose-600">Rent.</span>
            </a>

            <div class="hidden md:flex space-x-8 text-sm font-semibold text-slate-700">

                <a href="/"
                class="hover:text-rose-600 transition
                        {{ request()->routeIs('home') ? 'text-rose-600 font-bold' : '' }}">
                    HOME
                </a>

                <a href="{{ route('public.tool.index') }}"
                class="hover:text-rose-600 transition
                        {{ request()->routeIs('public.tool.index') || request()->routeIs('public.tool.show') ? 'text-rose-600 font-bold' : '' }}">
                    CATALOG
                </a>

            </div>

            <div class="flex items-center space-x-3 md:space-x-5">

            @auth
                @if(Auth::user()->role === 'customer')
                    <a href="{{ route('rentals.history') }}" class="relative p-2 bg-white rounded-full shadow-sm hover:text-rose-600 transition group">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>

                        @php
                            $activeCount = \App\Models\Rental::where('user_id', Auth::id())->where('status', 'active')->count();
                        @endphp
                        @if($activeCount > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-red-100 transform translate-x-1/4 -translate-y-1/4 bg-rose-600 rounded-full">
                                {{ $activeCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('rentals.create') }}" class="relative p-2 bg-white rounded-full shadow-sm hover:text-rose-600 transition group mr-2">

                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>

                        @if(session('cart', []))
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-red-100 transform translate-x-1/4 -translate-y-1/4 bg-rose-600 rounded-full">
                                {{ count(session('cart', [])) }}
                            </span>
                        @endif
                    </a>
                @endif

                <div class="relative" x-data="{ open: false }">

                    <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                        <div class="text-right hidden md:block">
                            <div class="text-sm font-bold text-slate-700 hover:text-rose-600 transition">{{ Auth::user()->name }}</div>
                            <div class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">{{ Auth::user()->role }}</div>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=0f172a&color=fff"
                            class="w-10 h-10 rounded-full border-2 border-white shadow-sm hover:border-rose-200 transition">
                    </button>

                    <div x-show="open"
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 border border-slate-100 z-50"
                        style="display: none;">

                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-rose-50 hover:text-rose-600">
                                ⚡ Admin Dashboard
                            </a>
                        @else
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-rose-50 hover:text-rose-600">
                                🛍️ Katalog Sewa
                            </a>
                            <a href="{{ route('rentals.history') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-rose-50 hover:text-rose-600">
                                📦 Riwayat Saya
                            </a>
                        @endif

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-rose-50 hover:text-rose-600">
                            👤 Edit Profile
                        </a>

                        <div class="border-t border-slate-100 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold">
                                🚪 Logout
                            </button>
                        </form>
                    </div>
                </div>

            @else
                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-rose-600 transition">
                    LOGIN
                </a>
                <a href="{{ route('register') }}" class="px-6 py-2.5 bg-slate-900 text-white rounded-full text-sm font-bold hover:bg-rose-600 transition shadow-lg shadow-slate-200 transform hover:-translate-y-0.5">
                    REGISTER
                </a>
            @endauth

        </div>
        </div>
    </nav>

    <main class="-mt-20">
        {{ $slot }}
    </main>

    <footer class="bg-rose-50 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 border-b border-rose-100 pb-12 mb-8">
                <div>
                    <a href="/" class="text-2xl font-black tracking-tighter text-slate-900">
                        Go<span class="text-rose-600">Rent.</span>
                    </a>
                    <p class="text-sm text-slate-500">Sewa alat kamera profesional dengan mudah, murah, dan terpercaya.</p>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-4">Layanan</h4>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="#" class="hover:text-rose-600">Sewa Harian</a></li>
                        <li><a href="#" class="hover:text-rose-600">Sewa Mingguan</a></li>
                        <li><a href="#" class="hover:text-rose-600">Member Pro</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-4">Informasi</h4>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="#" class="hover:text-rose-600">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-rose-600">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-rose-600">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-4">Kontak</h4>
                    <p class="text-sm text-slate-500">support@gorent.com</p>
                    <p class="text-sm text-slate-500">+62 812 3456 7890</p>
                </div>
            </div>

            <div class="text-center text-sm text-slate-400">
                &copy; {{ date('Y') }} GoRent Indonesia. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>