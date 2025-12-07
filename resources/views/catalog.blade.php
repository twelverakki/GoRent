<x-frontend-layout>


<header class="bg-gray-50 pt-20">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <form action="{{ route('public.tool.index') }}" method="GET">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Find products..." class="w-full pl-10 border-gray-200 bg-gray-50 rounded-3xl text-sm focus:ring-rose-200 focus:border-rose-300 ring-rose-200 transition">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </form>
    </div>
</header>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse ($tools as $tool)
            @include('components.card-tool', ['tool' => $tool])
        @empty
            <div class="col-span-4 text-center py-10 bg-white rounded-lg shadow-lg">
                <p class="text-xl text-gray-500">Maaf, saat ini belum ada alat yang tersedia.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $tools->links() }}
    </div>

</div>
</x-frontend-layout>
