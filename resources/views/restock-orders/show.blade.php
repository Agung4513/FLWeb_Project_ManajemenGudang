@extends('layouts.app')
@section('title', 'Detail PO ' . $restockOrder->po_number)
@section('page-title', 'Detail Purchase Order')

@section('content')
<div class="max-w-5xl mx-auto py-8">

    @php
        $backRoute = auth()->user()->role == 'supplier' ? route('supplier.restock-orders.index') : route('restock-orders.index');
    @endphp
    <a href="{{ $backRoute }}" class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-medium mb-6 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar
    </a>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-slate-800 to-slate-900 text-white p-8 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-black">{{ $restockOrder->po_number }}</h1>
                <p class="text-slate-400 mt-1 text-sm">Dibuat pada {{ $restockOrder->order_date->format('d F Y') }} oleh <span class="text-white font-bold">{{ $restockOrder->manager->name }}</span></p>
            </div>
            <div class="mt-4 md:mt-0 text-right">
                <p class="text-xs uppercase font-bold text-slate-400 mb-1">Status Pesanan</p>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-500',
                        'confirmed_by_supplier' => 'bg-blue-500',
                        'in_transit' => 'bg-indigo-500',
                        'received' => 'bg-emerald-500',
                    ];
                    $color = $statusColors[$restockOrder->status] ?? 'bg-gray-500';
                @endphp
                <span class="px-4 py-2 rounded-lg {{ $color }} text-white font-bold text-sm shadow-lg">
                    {{ strtoupper(str_replace('_', ' ', $restockOrder->status)) }}
                </span>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-gray-100">
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase mb-4">Informasi Supplier</h3>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl mr-4">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-lg">{{ $restockOrder->supplier->name }}</p>
                        <p class="text-slate-500 text-sm">{{ $restockOrder->supplier->email }}</p>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase mb-4">Jadwal Pengiriman</h3>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center text-xl mr-4">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-lg">{{ $restockOrder->expected_delivery_date->format('d F Y') }}</p>
                        <p class="text-slate-500 text-sm">Estimasi Tiba</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Rincian Barang</h3>
            <div class="overflow-hidden rounded-xl border border-gray-200">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase">
                        <tr>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4 text-center">Jumlah Dipesan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($restockOrder->items as $item)
                        <tr>
                            <td class="px-6 py-4 font-bold text-slate-700">
                                {{ $item->product->name }} <span class="text-xs text-slate-400 font-normal ml-1">({{ $item->product->sku }})</span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-indigo-600 bg-indigo-50/30">
                                {{ $item->quantity }} Unit
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($restockOrder->notes || $restockOrder->supplier_notes)
        <div class="px-8 pb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($restockOrder->notes)
            <div class="bg-gray-50 p-4 rounded-xl text-sm text-gray-600">
                <strong class="block mb-1 text-gray-800">Catatan Manager:</strong> {{ $restockOrder->notes }}
            </div>
            @endif
            @if($restockOrder->supplier_notes)
            <div class="bg-blue-50 p-4 rounded-xl text-sm text-blue-800">
                <strong class="block mb-1">Catatan Supplier:</strong> {{ $restockOrder->supplier_notes }}
            </div>
            @endif
        </div>
        @endif

        <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex justify-end gap-4">

            @if(auth()->user()->role === 'supplier' && $restockOrder->status === 'pending')
                <form action="{{ route('restock-orders.supplier-confirm', $restockOrder) }}" method="POST" class="w-full md:w-auto">
                    @csrf
                    <div class="mb-4">
                        <textarea name="supplier_notes" class="w-full border rounded-lg p-2 text-sm" placeholder="Catatan untuk gudang (opsional)..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-blue-700 shadow-lg transition" onclick="return confirm('Konfirmasi pesanan ini?')">
                        <i class="fa-solid fa-check-double mr-2"></i> Terima Pesanan
                    </button>
                </form>
            @endif

            @if(auth()->user()->role === 'manager' && in_array($restockOrder->status, ['confirmed_by_supplier', 'in_transit']))
                <form action="{{ route('restock-orders.receive', $restockOrder) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-emerald-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-emerald-700 shadow-lg transition" onclick="return confirm('Tandai barang sudah sampai? Ingat: Stok belum bertambah otomatis.')">
                        <i class="fa-solid fa-box-open mr-2"></i> Barang Diterima
                    </button>
                </form>
            @endif

        </div>
    </div>

    @if(auth()->user()->role === 'manager' && $restockOrder->status === 'received')
    <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-xl text-yellow-800 shadow-sm flex items-start">
        <i class="fa-solid fa-lightbulb text-xl mr-3 mt-1"></i>
        <div>
            <p class="font-bold">Barang sudah diterima.</p>
            <p class="text-sm">Silakan instruksikan Staff untuk menghitung fisik dan membuat <strong>Transaksi Masuk</strong> agar stok di sistem bertambah.</p>
        </div>
    </div>
    @endif
</div>
@endsection
