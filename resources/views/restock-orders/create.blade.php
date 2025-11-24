@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto py-10 px-6">
    <h1 class="text-4xl font-extrabold text-indigo-700 mb-8">Buat Restock Order Baru</h1>

    <form action="{{ route('restock-orders.store') }}" method="POST" class="bg-white rounded-2xl shadow-2xl p-8">
        @csrf

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-lg font-semibold mb-2">Supplier</label>
                <select name="supplier_id" required class="w-full border-2 rounded-lg px-4 py-3 @error('supplier_id') border-red-500 @enderror">
                    <option value="">Pilih Supplier</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-lg font-semibold mb-2">Estimasi Tiba</label>
                <input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date') }}"
                       required class="w-full border-2 rounded-lg px-4 py-3 @error('expected_delivery_date') border-red-500 @enderror">
                @error('expected_delivery_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-lg font-semibold mb-2">Catatan (Opsional)</label>
            <textarea name="notes" rows="3" class="w-full border-2 rounded-lg px-4 py-3">{{ old('notes') }}</textarea>
        </div>

        <div id="items-container" class="space-y-4 mb-8">
            <div class="item-row grid grid-cols-12 gap-4 items-center bg-gray-50 p-4 rounded-lg">
                <div class="col-span-7">
                    <select name="items[0][product_id]" required class="w-full border rounded-lg px-4 py-3">
                        <option value="">Pilih Produk</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} (Stok: {{ $p->current_stock }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-4">
                    <input type="number" name="items[0][quantity]" min="1" required placeholder="Jumlah" class="w-full border rounded-lg px-4 py-3">
                </div>
                <div class="col-span-1">
                    <button type="button" onclick="this.closest('.item-row').remove()" class="text-red-600 font-bold">Hapus</button>
                </div>
            </div>
        </div>

        <button type="button" onclick="addItem()" class="bg-blue-600 text-white px-6 py-3 rounded-lg mb-6">+ Tambah Item</button>

        <button type="submit"
                onclick="return confirm('YAKIN DATA SUDAH BENAR?\n\nSetelah dikirim, PO TIDAK BISA DIUBAH lagi!')"
                class="bg-gradient-to-r from-indigo-600 to-purple-700 text-white px-16 py-6 rounded-xl text-2xl font-bold shadow-xl hover:scale-105 transition">
            KIRIM RESTOCK ORDER KE SUPPLIER
        </button>
    </form>
</div>

<script>
let i = 1;
function addItem() {
    const container = document.getElementById('items-container');
    const row = container.querySelector('.item-row').cloneNode(true);
    row.querySelectorAll('input, select').forEach(el => {
        el.name = el.name.replace('[0]', '[' + i + ']');
        el.value = '';
    });
    container.appendChild(row);
    i++;
}
</script>
@endsection
