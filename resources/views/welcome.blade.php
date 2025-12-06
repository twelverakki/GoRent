<x-frontend-layout>

    <div class="relative bg-gradient-to-br from-rose-50 via-white to-rose-100 overflow-hidden min-h-screen flex items-center">

        <div class="absolute top-0 right-0 w-2/3 h-full bg-gradient-to-l from-rose-200/50 to-transparent skew-x-12 translate-x-20"></div>
        <div class="absolute bottom-10 left-10 w-20 h-20 bg-orange-400 rounded-full blur-2xl opacity-30"></div>
        <div class="absolute top-40 right-40 w-72 h-72 bg-rose-400 rounded-full blur-3xl opacity-20"></div>

        <div class="relative max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 items-center gap-12 -mt-20">

            <div class="z-10">
                <div class="inline-block px-4 py-2 bg-white rounded-full shadow-sm text-xs font-bold text-rose-500 mb-6 border border-rose-100">
                    🚀 #1 RENTAL KAMERA DI INDONESIA
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-slate-900 leading-tight mb-6">
                    We Eat <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-500 to-orange-500">Photography.</span>
                </h1>
                <p class="text-lg text-slate-500 mb-8 max-w-md">
                    Sewa peralatan kamera, lensa, dan lighting profesional untuk project kreatifmu. Mudah, cepat, dan terpercaya.
                </p>

                <div class="flex items-center gap-4">
                    <a href="{{ route('public.tool.index') }}" class="px-8 py-4 bg-slate-900 text-white rounded-full font-bold shadow-xl shadow-rose-200 hover:bg-rose-600 transition transform hover:-translate-y-1">
                        SHOP COLLECTION
                    </a>
                    <a href="#featured" class="px-8 py-4 bg-white text-slate-700 rounded-full font-bold shadow-sm border border-slate-100 hover:bg-slate-50 transition">
                        Lihat Promo
                    </a>
                </div>
            </div>

            <div class="relative z-10 flex justify-center">
                <div class="relative w-full max-w-lg">
                    <div class="absolute inset-0 bg-gradient-to-tr from-rose-200 to-orange-100 rounded-full blur-xl opacity-60 scale-90"></div>

                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl border border-slate-50 flex items-center gap-3 animate-bounce">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold">CONDITION</p>
                            <p class="text-sm font-bold text-slate-800">Perfect 100%</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="relative h-80 rounded-[2rem] overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-purple-600"></div>
                <div class="absolute inset-0 p-8 flex flex-col justify-between z-10">
                    <div>
                        <h3 class="text-3xl font-bold text-white mb-2">Cinema <br> Lenses</h3>
                        <p class="text-indigo-100 text-sm">6k+ Full Frame For All</p>
                    </div>
                    <a href="{{ route('public.tool.index', ['search' => 'lensa']) }}" class="w-max px-6 py-2 bg-white/20 backdrop-blur border border-white/30 text-white rounded-full text-xs font-bold hover:bg-white hover:text-indigo-600 transition">
                        READ MORE
                    </a>
                </div>
                <img src="https://pngimg.com/d/camera_lens_PNG125.png" class="absolute -bottom-10 -right-10 w-48 opacity-50 group-hover:scale-110 group-hover:rotate-12 transition duration-500">
            </div>

            <div class="relative h-80 rounded-[2rem] overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-teal-400 to-emerald-500"></div>
                <div class="absolute inset-0 p-8 flex flex-col justify-between z-10">
                    <div>
                        <h3 class="text-3xl font-bold text-white mb-2">Mirrorless <br> Reinvented.</h3>
                        <p class="text-teal-50 text-sm">Weekend Sale 20-45% off</p>
                    </div>
                    <a href="{{ route('public.tool.index', ['search' => 'kamera']) }}" class="w-max px-6 py-2 bg-white/20 backdrop-blur border border-white/30 text-white rounded-full text-xs font-bold hover:bg-white hover:text-teal-600 transition">
                        READ MORE
                    </a>
                </div>
                <img src="" class="absolute bottom-5 -right-5 w-40 opacity-60 group-hover:scale-110 group-hover:-rotate-12 transition duration-500">
            </div>

            <div class="relative h-80 rounded-[2rem] overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-400 to-rose-500"></div>
                <div class="absolute inset-0 p-8 flex flex-col justify-between z-10">
                    <div>
                        <h3 class="text-3xl font-bold text-white mb-2">Digital <br> Cinema Cam</h3>
                        <p class="text-orange-100 text-sm">Canon EOS C300 Mark II</p>
                    </div>
                    <a href="{{ route('public.tool.index', ['search' => 'cinema']) }}" class="w-max px-6 py-2 bg-white/20 backdrop-blur border border-white/30 text-white rounded-full text-xs font-bold hover:bg-white hover:text-rose-600 transition">
                        READ MORE
                    </a>
                </div>
                <img src="" class="absolute bottom-0 -right-10 w-56 opacity-60 group-hover:scale-110 transition duration-500">
            </div>

        </div>
    </div>

    <div id="featured" class="max-w-7xl mx-auto px-6 py-10 mb-20">
        <div class="text-center mb-12">
            <span class="text-rose-500 font-bold text-sm tracking-wider uppercase">Trending Now</span>
            <h2 class="text-4xl font-black text-slate-900 mt-2">Featured Products</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($tools as $tool)
                @include('components.card-tool', ['tool' => $tool])
            @endforeach
        </div>
    </div>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>

</x-frontend-layout>