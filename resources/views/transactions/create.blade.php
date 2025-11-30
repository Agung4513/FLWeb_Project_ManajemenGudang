@extends('layouts.app')

@section('title', 'Transaksi Baru')
@section('page-title', 'Input Transaksi Baru')

@section('content')
<div class="max-w-7xl mx-auto pb-20">

    @if(isset($restockOrder))
        <div class="bg-indigo-600 text-white p-6 rounded-t-3xl shadow-lg -mb-6 relative z-10 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold flex items-center">
                    <i class="fa-solid fa-link mr-2"></i> Memproses Restock Order: {{ $restockOrder->po_number }}
                </h2>
                <p class="text-indigo-200 text-sm">Data supplier dan barang telah diisi otomatis. Silakan verifikasi jumlah fisik.</p>
            </div>
            <a href="{{ route('staff.dashboard') }}" class="text-white bg-indigo-700 hover:bg-indigo-800 px-4 py-2 rounded-lg font-bold text-sm">Batal</a>
        </div>
    @endif

    <div class="text-center mb-10 pt-6">
        <h1 class="text-5xl font-extrabold bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent">
            TRANSAKSI BARU
        </h1>
        <p class="text-gray-500 mt-2 font-bold uppercase tracking-widest">
            STAFF: <span class="text-slate-800">{{ auth()->user()->name }}</span>
        </p>
    </div>

    <form action="{{ route('staff.transactions.store') }}" method="POST" class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
        @csrf

        @if(isset($restockOrder))
            <input type="hidden" name="restock_order_id" value="{{ $restockOrder->id }}">
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200">
                <label class="block text-sm font-bold text-gray-500 uppercase mb-4">Jenis Transaksi</label>
                <div class="flex gap-4">
                    <label class="flex-1 cursor-pointer group {{ isset($restockOrder) ? 'opacity-50 pointer-events-none' : '' }}">
                        <input type="radio" name="type" value="outgoing" class="peer sr-only transaction-type" {{ !isset($restockOrder) ? 'checked' : '' }}>
                        <div class="p-4 rounded-xl border-2 border-gray-200 text-center peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 transition group-hover:border-red-200">
                            <i class="fa-solid fa-arrow-up text-2xl mb-1 block"></i>
                            <span class="font-bold">PENJUALAN</span>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="type" value="incoming" class="peer sr-only transaction-type" {{ isset($restockOrder) ? 'checked' : '' }}>
                        <div class="p-4 rounded-xl border-2 border-gray-200 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition group-hover:border-blue-200">
                            <i class="fa-solid fa-arrow-down text-2xl mb-1 block"></i>
                            <span class="font-bold">PEMBELIAN</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-200 outline-none">
                </div>

                <div>
                    <div id="field-customer" class="{{ isset($restockOrder) ? 'hidden' : '' }}">
                        <label class="block text-sm font-bold text-slate-700 mb-1">Nama Customer</label>
                        <input type="text" name="customer_name" id="input-customer" class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-200 outline-none" placeholder="Contoh: Toko Maju Jaya">
                    </div>

                    <div id="field-supplier" class="{{ !isset($restockOrder) ? 'hidden' : '' }}">
                        <label class="block text-sm font-bold text-slate-700 mb-1">Pilih Supplier</label>
                        <div class="relative">
                            <select name="supplier_id" id="input-supplier" class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-200 outline-none appearance-none bg-white {{ isset($restockOrder) ? 'pointer-events-none bg-gray-100 text-gray-500' : '' }}">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ (isset($restockOrder) && $restockOrder->supplier_id == $supplier->id) ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-slate-800">Daftar Barang</h3>
                @if(!isset($restockOrder))
                <button type="button" id="add-item" class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg font-bold hover:bg-indigo-200 transition text-sm">
                    + Tambah Baris
                </button>
                @endif
            </div>

            <div id="items-container" class="space-y-4">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-gray-100">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Catatan</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 border rounded-xl focus:outline-none">{{ isset($restockOrder) ? "Penerimaan dari PO: " . $restockOrder->po_number : '' }}</textarea>
            </div>
            <div class="flex flex-col justify-center items-center bg-slate-900 text-white rounded-2xl p-6">
                <span class="text-slate-400 text-xs font-bold uppercase">Total Estimasi</span>
                <span class="text-4xl font-black text-green-400" id="total-display">Rp 0</span>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-4">
            <a href="{{ route('staff.transactions.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition">Batal</a>
            <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-green-700 shadow-lg transition transform hover:scale-105">
                Simpan Transaksi
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const products = @json($products);

    const restockItems = @json(isset($restockOrder) ? $restockOrder->items : null);

    let itemIndex = 0;
    const container = document.getElementById('items-container');
    const totalDisplay = document.getElementById('total-display');
    const radioTypes = document.querySelectorAll('input[name="type"]');
    const fieldCustomer = document.getElementById('field-customer');
    const fieldSupplier = document.getElementById('field-supplier');
    const inputCustomer = document.getElementById('input-customer');
    const inputSupplier = document.getElementById('input-supplier');

    function createRow(index, prefilledData = null) {
        let options = '<option value="">Pilih Produk</option>';
        products.forEach(p => {
            let selected = (prefilledData && prefilledData.product_id == p.id) ? 'selected' : '';
            options += `<option value="${p.id}" ${selected} data-sell-price="${p.sell_price}" data-buy-price="${p.buy_price}">
                ${p.name} (Stok: ${p.current_stock})
            </option>`;
        });

        let qtyVal = prefilledData ? prefilledData.quantity : 1;

        return `
            <div class="item-row grid grid-cols-12 gap-3 bg-gray-50 p-4 rounded-xl border border-gray-200 animate-fade-in">
                <div class="col-span-6 md:col-span-5">
                    <select name="items[${index}][product_id]" class="product-select w-full px-3 py-2 rounded-lg border focus:outline-none" required>
                        ${options}
                    </select>
                </div>
                <div class="col-span-3 md:col-span-2">
                    <input type="number" name="items[${index}][quantity]" value="${qtyVal}" min="1" class="quantity-input w-full px-3 py-2 text-center rounded-lg border" placeholder="Qty">
                </div>
                <div class="col-span-3 md:col-span-4">
                    <input type="number" name="items[${index}][price]" class="price-input w-full px-3 py-2 text-right rounded-lg border" placeholder="Harga Satuan" readonly>
                </div>
                <div class="col-span-12 md:col-span-1 text-right flex items-end justify-end">
                    <button type="button" class="remove-item text-red-500 hover:text-red-700 font-bold text-xl px-2">&times;</button>
                </div>
            </div>
        `;
    }

    if (restockItems) {
        restockItems.forEach(item => {
            const div = document.createElement('div');
            div.innerHTML = createRow(itemIndex++, item);
            container.appendChild(div.firstElementChild);
        });
        updateFormType();
    } else {
        document.getElementById('add-item').addEventListener('click', () => {
            const div = document.createElement('div');
            div.innerHTML = createRow(itemIndex++);
            container.appendChild(div.firstElementChild);
        });
        document.getElementById('add-item').click();
    }

    function updateFormType() {
        const type = document.querySelector('input[name="type"]:checked').value;
        if (type === 'outgoing') {
            fieldCustomer.classList.remove('hidden');
            fieldSupplier.classList.add('hidden');
            inputCustomer.setAttribute('required', 'required');
            inputSupplier.removeAttribute('required');
        } else {
            fieldCustomer.classList.add('hidden');
            fieldSupplier.classList.remove('hidden');
            inputSupplier.setAttribute('required', 'required');
            inputCustomer.removeAttribute('required');
        }
        updatePrices();
    }

    function updatePrices() {
        const type = document.querySelector('input[name="type"]:checked').value;
        document.querySelectorAll('.item-row').forEach(row => {
            const select = row.querySelector('.product-select');
            const priceInput = row.querySelector('.price-input');
            const option = select.options[select.selectedIndex];
            if (option && option.value) {
                const price = type === 'outgoing' ? option.dataset.sellPrice : option.dataset.buyPrice;
                priceInput.value = price;
            }
        });
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        container.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            total += qty * price;
        });
        totalDisplay.innerText = 'Rp ' + parseInt(total).toLocaleString('id-ID');
    }

    radioTypes.forEach(radio => radio.addEventListener('change', updateFormType));

    container.addEventListener('change', (e) => {
        if(e.target.classList.contains('product-select')) updatePrices();
    });

    container.addEventListener('input', (e) => {
        if(e.target.matches('.quantity-input')) calculateTotal();
    });

    container.addEventListener('click', (e) => {
        if(e.target.classList.contains('remove-item')) {
            if(container.querySelectorAll('.item-row').length > 1) {
                e.target.closest('.item-row').remove();
                calculateTotal();
            } else {
                alert('Minimal satu barang.');
            }
        }
    });

    setTimeout(updatePrices, 100);
});
</script>

<style>
    .animate-fade-in { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection
