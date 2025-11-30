@extends('layouts.app')
@section('title', 'Buat PO Baru')
@section('page-title', 'Buat Restock Order')

@section('content')
<div class="max-w-5xl mx-auto pb-20">
    <a href="{{ route('restock-orders.index') }}" class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-medium mb-6 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar
    </a>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-8 py-6 text-white">
            <h2 class="text-2xl font-extrabold">Form Purchase Order</h2>
            <p class="text-amber-100 text-sm mt-1">Buat permintaan stok barang ke Supplier.</p>
        </div>

        <form action="{{ route('restock-orders.store') }}" method="POST" class="p-8 md:p-10">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Supplier <span class="text-red-500">*</span></label>
                    <select name="supplier_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-amber-500 focus:outline-none" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Estimasi Tiba <span class="text-red-500">*</span></label>
                    <input type="date" name="expected_delivery_date" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-amber-500 focus:outline-none" required>
                </div>
            </div>

            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Barang yang Dipesan</h3>
                    <button type="button" id="add-item" class="bg-amber-100 text-amber-700 px-4 py-2 rounded-lg font-bold hover:bg-amber-200 transition text-sm">
                        + Tambah Baris
                    </button>
                </div>

                <div id="items-container" class="space-y-4">
                    <div class="item-row grid grid-cols-12 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <div class="col-span-8">
                            <select name="items[0][product_id]" class="w-full px-3 py-2 rounded-lg border focus:outline-none" required>
                                <option value="">Pilih Produk</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->sku }} - {{ $p->name }} (Sisa: {{ $p->current_stock }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-3">
                            <input type="number" name="items[0][quantity]" class="w-full px-3 py-2 text-center rounded-lg border" placeholder="Qty" min="1" required>
                        </div>
                        <div class="col-span-1 text-right">
                            <button type="button" class="remove-item text-red-500 hover:text-red-700 font-bold text-xl">&times;</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Tambahan</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-amber-500 focus:outline-none"></textarea>
            </div>

            <div class="flex justify-end pt-6 border-t border-gray-100">
                <button type="submit" class="bg-gradient-to-r from-amber-500 to-orange-600 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg hover:scale-105 transition transform">
                    Kirim PO
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const products = @json($products);
    let itemIndex = 1;
    const container = document.getElementById('items-container');

    function createRow(index) {
        let options = '<option value="">Pilih Produk</option>';
        products.forEach(p => {
            options += `<option value="${p.id}">${p.sku} - ${p.name} (Sisa: ${p.current_stock})</option>`;
        });

        return `
            <div class="item-row grid grid-cols-12 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-200 mt-4 animate-fade-in">
                <div class="col-span-8">
                    <select name="items[${index}][product_id]" class="w-full px-3 py-2 rounded-lg border focus:outline-none" required>${options}</select>
                </div>
                <div class="col-span-3">
                    <input type="number" name="items[${index}][quantity]" class="w-full px-3 py-2 text-center rounded-lg border" placeholder="Qty" min="1" required>
                </div>
                <div class="col-span-1 text-right">
                    <button type="button" class="remove-item text-red-500 hover:text-red-700 font-bold text-xl">&times;</button>
                </div>
            </div>
        `;
    }

    document.getElementById('add-item').addEventListener('click', () => {
        const div = document.createElement('div');
        div.innerHTML = createRow(itemIndex++);
        container.appendChild(div.firstElementChild);
    });

    container.addEventListener('click', (e) => {
        if(e.target.classList.contains('remove-item')) {
            if(container.querySelectorAll('.item-row').length > 1) {
                e.target.closest('.item-row').remove();
            } else {
                alert('Minimal satu barang.');
            }
        }
    });
});
</script>
<style>
    .animate-fade-in { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection
