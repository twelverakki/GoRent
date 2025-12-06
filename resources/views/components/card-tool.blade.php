<div class="bg-white p-4 rounded-3xl shadow-sm hover:shadow-xl transition duration-300 border border-slate-200 group">
    <div class="relative w-full aspect-square bg-gray-50 rounded-2xl mb-4 overflow-hidden flex items-center justify-center">
        @if($tool->image)
            <img src="{{ Storage::url($tool->image) }}" class="w-full h-full object-cover aspect-square group-hover:scale-110 transition duration-500">
        @else
            <span class="text-gray-300">No Image</span>
        @endif

    </div>

    <div class="px-2">
        <p class="text-xs text-slate-400 font-bold mb-1">{{ $tool->category->name }}</p>
        <h3 class="font-bold text-slate-800 text-lg mb-2 line-clamp-1">{{ $tool->name }}</h3>

        <div class="flex items-center justify-between mt-4">
            <span class="text-rose-500 font-black text-lg">Rp {{ number_format($tool->price_per_day) }}</span>

            <a href="{{ route('public.tool.show', ['tool' => $tool->id]) }}" class="w-10 h-10 rounded-full bg-slate-100 text-slate-800 flex items-center justify-center hover:bg-slate-900 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </a>
        </div>
    </div>
</div>