@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-700 text-white p-8">
            <h1 class="text-4xl font-bold">INVOICE</h1>
            <p class="text-2xl mt-2">{{ $transaction->transaction_number }}</p>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                    <p class="text-gray-600">Tanggal</p>
                    <p class="text-2xl font-bold">{{ $transaction->date->format('d F Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-600">Tipe Transaksi</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $transaction->type == 'outgoing' ? 'PENJUALAN' : 'RESTOCK' }}
                    </p>
                </div>
            </div>

            @if($transaction->customer_name)
            <div class="mb-6">
                <p class="text-gray-600">Pelanggan</p>
                <p class="text-xl font-bold">{{ $transaction->customer_name }}</p>
            </div>
            @endif

            @if($transaction->supplier)
            <div class="mb-6">
                <p class="text-gray-600">Supplier</p>
                <p class="text-xl font-bold">{{ $transaction->supplier->name }}</p>
            </div>
            @endif

            <table class="w-full mt-8">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left py-3 px-4">Produk</th>
                        <th class="text-center py-3 px-4">Qty</th>
                        <th class="text-right py-3 px-4">Harga</th>
                        <th class="text-right py-3 px-4">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $item)
                    <tr class="border-b">
                        <td class="py-4 px-4">{{ $item->product->name }}</td>
                        <td class="text-center py-4 px-4">{{ $item->quantity }}</td>
                        <td class="text-right py-4 px-4">Rp {{ number_format($item->price_at_transaction, 0, ',', '.') }}</td>
                        <td class="text-right py-4 px-4 font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-8 text-right border-t-4 border-indigo-600 pt-6">
                <p class="text-3xl font-black text-green-700">
                    TOTAL: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                </p>
                @if($transaction->type == 'outgoing')
                <p class="text-xl text-gray-600 mt-2">
                    Keuntungan: Rp {{ number_format($transaction->total_profit, 0, ',', '.') }}
                </p>
                @endif
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('transactions.index') }}"
                   class="bg-indigo-600 text-white px-12 py-4 rounded-xl text-xl font-bold hover:bg-indigo-700 transition">
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
