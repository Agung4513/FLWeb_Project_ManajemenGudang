@extends('layouts.app')
@section('title', 'Transaksi Baru')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 py-12 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-7xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-700 bg-clip-text text-transparent">
                {{ auth()->user()->role == 'staff' ? 'TRANSAKSI PENJUALAN' : 'TRANSAKSI BARU' }}
            </h1>
            <p class="text-3xl text-gray-700 mt-4 font-medium">GUDANG JAYA • {{ auth()->user()->name }}</p>
        </div>

        <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
            @csrf

            @if(in_array(auth()->user()->role, ['admin','manager','staff']))
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-10 mb-8 border-l-8 border-green-500">
                <h2 class="text-4xl font-extrabold text-green-700 mb-6">Penjualan ke Pelanggan</h2>
                <input type="text" name="customer_name" placeholder="Nama Pelanggan (wajib untuk staff)"
                       class="w-full text-2xl p-6 border-4 border-green-300 rounded-2xl focus:border-green-600 focus:ring-4 focus:ring-green-200 transition-all"
                       {{ auth()->user()->role == 'staff' ? 'required' : '' }}>
            </div>
            @endif

            @if(in_array(auth()->user()->role, ['admin','manager']))
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-10 mb-8 border-l-8 border-blue-500">
                <h2 class="text-4xl font-extrabold text-blue-700 mb-6">Restock dari Supplier</h2>
                <select name="supplier_id" class="w-full text-2xl p-6 border-4 border-blue-300 rounded-2xl focus:border-blue-600 focus:ring-4 focus:ring-blue-200 transition-all">
                    <option value="">Pilih Supplier (opsional)</option>
                    @foreach(\App\Models\User::where('role', 'supplier')->get() as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-10">
                <h3 class="text-4xl font-extrabold text-gray-800 mb-8 text-center">DAFTAR ITEM</h3>

                <div id="items-container" class="space-y-6">
                </div>

                <div class="text-center mt-10">
                    <button type="button" onclick="addItem()"
                            class="bg-gradient-to-r from-indigo-600 to-purple-700 text-white font-extrabold text-2xl px-16 py-8 rounded-3xl shadow-2xl hover:scale-110 transition-all duration-300">
                        + TAMBAH ITEM
                    </button>
                </div>

                <div class="mt-12 p-10 bg-gradient-to-r from-green-50 to-emerald-50 rounded-3xl border-8 border-green-400">
                    <div class="text-right">
                        <p class="text-5xl font-extrabold text-gray-800">TOTAL BAYAR</p>
                        <p class="text-8xl font-black text-green-700 mt-4">
                            Rp <span id="total-display">0</span>
                        </p>
                    </div>
                </div>

                <div class="text-center mt-12">
                    <button type="submit"
                            class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white font-extrabold text-5xl px-32 py-12 rounded-3xl shadow-3xl hover:shadow-4xl hover:scale-105 transition-all duration-500">
                        SIMPAN TRANSAKSI
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const products = @json($products);

let itemIndex = 0;

function addItem() {
    const container = document.getElementById('items-container');
    const div = document.createElement('div');
    div.className = 'grid grid-cols-12 gap-6 p-8 bg-gradient-to-r from-gray-50 to-gray-100 rounded-3xl shadow-xl border-4 border-gray-300 item-row';

    div.innerHTML = `
        <div class="col-span-5">
            <select name="items[${itemIndex}][product_id]" required class="product-select w-full text-2xl p-6 border-4 border-purple-300 rounded-2xl focus:border-purple-600 focus:ring-4 focus:ring-purple-200 transition-all" onchange="updatePrice(this)">
                <option value="">Pilih Produk</option>
                ${products.map(p => `
                    <option value="${p.id}"
                            data-sell="${p.sell_price}"
                            data-buy="${p.buy_price}"
                            data-stock="${p.stock}">
                        ${p.name}
                    </option>
                `).join('')}
            </select>
        </div>
        <div class="col-span-3">
            <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1" required
                   class="quantity-input w-full text-3xl p-6 border-4 border-indigo-300 rounded-2xl text-center font-bold focus:border-indigo-600 focus:ring-4 focus:ring-indigo-200 transition-all"
                   oninput="updateTotal(this)">
        </div>
        <div class="col-span-3 text-right">
            <div class="text-4xl font-extrabold text-green-700 price-display">Rp 0</div>
        </div>
        <div class="col-span-1 text-center">
            <button type="button" onclick="this.closest('.item-row').remove(); calculateGrandTotal()"
                    class="text-red-600 hover:text-red-800 text-5xl font-bold hover:scale-125 transition">×</button>
        </div>
    `;

    container.appendChild(div);
    itemIndex++;
}

function updatePrice(select) {
    const row = select.closest('.item-row');
    const option = select.selectedOptions[0];
    if (!option || !option.value) {
        row.querySelector('.price-display').textContent = 'Rp 0';
        calculateGrandTotal();
        return;
    }

    const sellPrice = parseFloat(option.dataset.sell) || 0;
    const buyPrice = parseFloat(option.dataset.buy) || 0;
    const customerInput = document.querySelector('input[name="customer_name"]');
    const isSale = customerInput && (customerInput.value || customerInput.hasAttribute('required'));
    const price = isSale ? sellPrice : buyPrice;
    const qty = parseInt(row.querySelector('.quantity-input').value) || 1;
    const subtotal = price * qty;

    row.querySelector('.price-display').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    calculateGrandTotal();
}

function updateTotal(input) {
    const row = input.closest('.item-row');
    const select = row.querySelector('.product-select');
    if (!select.value) return;

    const option = select.selectedOptions[0];
    const sellPrice = parseFloat(option.dataset.sell) || 0;
    const buyPrice = parseFloat(option.dataset.buy) || 0;
    const customerInput = document.querySelector('input[name="customer_name"]');
    const isSale = customerInput && (customerInput.value || customerInput.hasAttribute('required'));
    const price = isSale ? sellPrice : buyPrice;
    const qty = parseInt(input.value) || 0;
    const subtotal = price * qty;

    row.querySelector('.price-display').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const select = row.querySelector('.product-select');
        const qtyInput = row.querySelector('.quantity-input');
        if (select?.value && qtyInput?.value) {
            const option = select.selectedOptions[0];
            const sellPrice = parseFloat(option.dataset.sell) || 0;
            const buyPrice = parseFloat(option.dataset.buy) || 0;
            const customerInput = document.querySelector('input[name="customer_name"]');
            const isSale = customerInput && (customerInput.value || customerInput.hasAttribute('required'));
            const price = isSale ? sellPrice : buyPrice;
            const qty = parseInt(qtyInput.value) || 0;
            total += price * qty;
        }
    });
    document.getElementById('total-display').textContent = total.toLocaleString('id-ID');
}

document.addEventListener('DOMContentLoaded', () => {
    addItem();
});
</script>
@endsection
