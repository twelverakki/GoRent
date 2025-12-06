<x-frontend-layout>


<header class="bg-gray-50 border-b border-gray-200 pt-20">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold leading-tight text-gray-900">
            Katalog Alat Sewa Kami
        </h1>
        <p class="mt-1 text-md text-gray-600">
            Temukan dan sewa peralatan terbaik untuk kebutuhan proyekmu.
        </p>
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
