<x-frontend-layout>
    <div class="py-12 pt-20 bg-gray-50 min-h-screen"
        x-data="{
            showDeleteModal: false, deleteUrl: '',
            showWarningModal: false, warningMessage: ''
        }"
        @show-warning.window="showWarningModal = true; warningMessage = $event.detail"
    >

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-slate-800 mb-8">Checkout Pesanan</h1>

            @if (count($tools))
            <form action="{{ route('rentals.store') }}" method="POST" id="booking-form">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <div class="lg:col-span-2 space-y-6">

                        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                            <h3 class="text-lg font-bold text-slate-800 mb-6">Jadwal Sewa</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-600 mb-2">Tanggal Ambil</label>
                                    <input type="date" name="start_date" id="start_date" min="{{ date('Y-m-d') }}" class="w-full px-5 py-3 bg-slate-50 border-none rounded-xl font-bold" required>
                                    <x-input-error :messages="$errors->get('start_date')" class="mt-2"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-600 mb-2">Tanggal Kembali</label>
                                    <input type="date" name="end_date" id="end_date" class="w-full px-5 py-3 bg-slate-50 border-none rounded-xl font-bold" required>
                                    <x-input-error :messages="$errors->get('end_date')" class="mt-2"/>
                                </div>
                            </div>

                        </div>

                        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                            <h3 class="text-lg font-bold text-slate-800 mb-6">Barang yang Disewa</h3>

                            @foreach($tools as $index => $tool)
                            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-50 last:mb-0 last:pb-0 last:border-none item-row">

                                <input type="hidden" name="tools[{{ $index }}][id]" value="{{ $tool->id }}">
                                <input type="hidden" class="item-price" value="{{ $tool->price_per_day }}">

                                <div class="w-20 h-20 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0">
                                    <img src="{{ Storage::url($tool->image) }}" class="w-full h-full object-cover">
                                </div>

                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-800">{{ $tool->name }}</h4>
                                    <p class="text-rose-500 font-bold text-sm">Rp {{ number_format($tool->price_per_day) }} <span class="text-slate-400 font-normal">/hari</span></p>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-1">
                                        <button type="button" onclick="updateQty(this, -1)" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg text-slate-500 hover:text-rose-500 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                        </button>
                                        <input type="number" name="tools[{{ $index }}][qty]" class="item-qty w-12 focus:ring-0 bg-transparent border-none text-center font-bold text-slate-700 -mr-2  text-sm appearance-none" value="1" min="1" max="{{ $tool->stock }}" readonly>
                                        <button type="button" onclick="updateQty(this, 1)" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg text-slate-500 hover:text-emerald-500 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                <button type="button"
                                        @click="showDeleteModal = true; deleteUrl = '{{ route('cart.remove', $tool->id) }}'"
                                        class="p-2 text-slate-300 hover:text-red-500 transition"
                                        title="Hapus dari keranjang">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>

                            </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 sticky top-8">
                            <h3 class="text-lg font-bold text-slate-800 mb-6">Ringkasan</h3>

                            <div class="space-y-3 text-sm text-slate-500 mb-6">
                                <div class="flex justify-between">
                                    <span>Total Barang</span>
                                    <span class="font-bold text-slate-700">{{ $tools->count() }} Item</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Durasi</span>
                                    <span class="font-bold text-slate-700" id="duration-display">0 Hari</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-4 border-t border-dashed border-slate-200 mb-6">
                                <span class="font-bold text-slate-800">Total Biaya</span>
                                <span class="text-2xl font-black text-rose-500" id="grand-total">Rp 0</span>
                            </div>

                            <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold hover:bg-rose-600 transition shadow-lg transform active:scale-95">
                                Proses Sewa
                            </button>
                        </div>
                    </div>

                </div>
            </form>
            @else
            <div class="w-full text-center bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                <img src="storage/images/empty-cart.png" alt="" class="w-44 mx-auto">
                <h3 class="text-lg font-bold text-slate-800 mb-6">Keranjangmu Kosong!</h3>
                <div>
                    <a href="{{route('public.tool.index')}}" class="bg-slate-900 text-white p-4 rounded-xl font-bold hover:bg-rose-600 transition shadow-lg transform active:scale-95">Telusuri barang</a>
                </div>
            </div>
            @endif

        </div>

        <x-delete-modal
            title="Hapus dari Keranjang?"
            description="Barang ini akan dihapus dari daftar sewa anda."
        />

        <x-info-modal />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. INISIALISASI ---
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const durationDisplay = document.getElementById('duration-display');
            const grandTotalDisplay = document.getElementById('grand-total');

            // --- 2. FUNGSI HITUNG TOTAL (Global Scope via Window) ---
            window.calculateTotal = function() {
                const start = new Date(startDateInput.value);
                const end = new Date(endDateInput.value);
                let days = 0;

                // Hitung Durasi Hari
                if (start && end && !isNaN(start) && !isNaN(end) && end >= start) {
                    const diffTime = Math.abs(end - start);
                    days = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    if (days === 0 && start.getDate() === end.getDate()) days = 1;
                }

                durationDisplay.innerText = days + ' Hari';

                // Hitung Grand Total
                let grandTotal = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    const price = parseFloat(row.querySelector('.item-price').value);
                    const qtyInput = row.querySelector('.item-qty');

                    // Ambil nilai qty (jika kosong anggap 0)
                    let qty = parseInt(qtyInput.value) || 0;

                    // Cek Max Stok (Safety Check)
                    let max = parseInt(qtyInput.getAttribute('max'));
                    if (qty > max) qty = max;

                    grandTotal += (price * qty * (days || 0));
                });

                grandTotalDisplay.innerText = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(grandTotal);
            };

            // --- 3. EVENT LISTENER ---

            // Listener Tanggal
            startDateInput.addEventListener('change', () => {
                endDateInput.min = startDateInput.value;
                window.calculateTotal();
            });
            endDateInput.addEventListener('change', window.calculateTotal);

            // Listener Input Manual Qty (Ketik Angka)
            document.querySelectorAll('.item-qty').forEach(input => {
                input.addEventListener('input', function() {
                    let val = parseInt(this.value);
                    let max = parseInt(this.getAttribute('max'));
                    let min = parseInt(this.getAttribute('min'));

                    if (val > max) {
                        this.value = max;
                        // Panggil Modal Warning
                        window.dispatchEvent(new CustomEvent('show-warning', {
                            detail: 'Maksimal stok tersedia hanya ' + max + ' unit!'
                        }));
                    }

                    if (val < min) this.value = min;

                    window.calculateTotal();
                });
            });

        });

        // --- 4. FUNGSI TOMBOL +/- (Di luar DOMContentLoaded) ---
        function updateQty(btn, change) {
            const wrapper = btn.closest('div');
            const input = wrapper.querySelector('input.item-qty');

            let currentVal = parseInt(input.value) || 1;
            let max = parseInt(input.getAttribute('max'));
            let min = parseInt(input.getAttribute('min'));

            let newVal = currentVal + change;

            if (newVal >= min && newVal <= max) {
                input.value = newVal;
                // Panggil kalkulasi ulang
                if (typeof window.calculateTotal === 'function') {
                    window.calculateTotal();
                }
            } else {
                // Jika melebihi batas, panggil modal
                if(newVal > max) {
                    window.dispatchEvent(new CustomEvent('show-warning', {
                        detail: 'Maksimal stok tersedia hanya ' + max + ' unit!'
                    }));
                }
            }
        }
    </script>
</x-frontend-layout>