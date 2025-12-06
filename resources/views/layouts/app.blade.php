<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - GoRent</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#F5F6FA] text-gray-700 antialiased">
    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 justify-between bg-white flex-shrink-0 hidden md:flex flex-col border-r border-gray-100">
            <div>
                <div class="h-20 flex items-center px-8 border-b border-gray-50">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-rose-500 hover:opacity-80 transition">
                        Go<span class="text-gray-800">Rent.</span>
                    </a>
                </div>
                @if (Auth::user()->role == 'admin')

                <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2 mb-auto">

                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </x-nav-link>

                    <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mr-3" viewBox="0 0 16 16"><path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/></svg>
                        Kelola Users
                    </x-nav-link>

                    <x-nav-link :href="route('admin.rentals.index')" :active="request()->routeIs('admin.rentals.*')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Rental History
                    </x-nav-link>

                    <x-nav-link :href="route('admin.tools.index')" :active="request()->routeIs('admin.tools.*')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mr-3" viewBox="0 0 16 16"><path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2zm3.564 1.426L5.596 5 8 5.961 14.154 3.5zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z"/></svg>
                        Inventaris Alat
                    </x-nav-link>

                </nav>
                @endif
            </div>

            <div class="flex-0 overflow-y-auto py-6 px-4 space-y-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    {{-- Gunakan x-nav-link sebagai tombol submit --}}
                    <x-nav-link :href="route('logout')"
                        class="shadow-inner w-full"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Logout
                    </x-nav-link>

                </form>


                <div class="flex gap-2">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=F43F5E&color=fff" class="inline h-full aspect-square rounded-xl shadow-sm">
                    <x-nav-link :active="true" class="shadow-inner inline w-full">
                        <div class="font-bold text-gray-700">{{ Auth::user()->name }}</div>
                    </x-nav-link>
                </div>
            </div>
        </aside>

        <main class="flex-1 pt-8 overflow-x-hidden overflow-y-auto px-8 pb-4">
            {{ $slot }}
        </main>
    </div>
</body>
</html>