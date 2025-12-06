@props([
    'triggerVariable' => 'showDeleteModal', // Nama variabel Alpine untuk show/hide
    'actionVariable' => 'deleteUrl',        // Nama variabel Alpine untuk URL Hapus
    'title' => 'Hapus Data Ini?',
    'description' => 'Tindakan ini tidak dapat dibatalkan. Data akan hilang permanen.'
])

<div x-show="{{ $triggerVariable }}"
     style="display: none;"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div @click.away="{{ $triggerVariable }} = false"
         class="bg-white rounded-[2rem] p-6 max-w-sm w-full shadow-2xl transform transition-all"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4">

        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-4 text-rose-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800">{{ $title }}</h3>
            <p class="text-sm text-slate-500 mt-2">{{ $description }}</p>
        </div>

        <div class="flex gap-3">
            <button @click="{{ $triggerVariable }} = false" class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition">
                Batal
            </button>

            <form :action="{{ $actionVariable }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-3 bg-rose-500 text-white font-bold rounded-xl hover:bg-rose-600 shadow-lg shadow-rose-200 transition">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>