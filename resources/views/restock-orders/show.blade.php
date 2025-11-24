@extends('layouts.app')
@section('title', 'Detail PO - ' . $restockOrder->po_number)
@section('page-title', 'Detail Purchase Order')

@section('content')
<div class="max-w-5xl mx-auto py-12">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-teal-600 to-cyan-700 text-white p-10">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-5xl font-bold">{{ $restockOrder->po_number }}</h1>
                    <p class="text-2xl mt-3">Purchase Order dari Gudang Jaya</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold">{{ $restockOrder->order_date->format('d/m/Y') }}</p>
                    <p class="text-xl">Tanggal Order</p>
                </div>
            </div>
        </div>

        <div class="p-10">
            <div class="grid grid-cols-2 gap-8 mb-10">
                <div class="bg-gray-50 p-8 rounded-2xl">
                    <h3 class="text-xl font-bold text-gray-700 mb-4">Informasi Pengiriman</h3>
                    <p><span class="font-semibold">Estimasi Tiba:</span>
                       <span class="text-lg font-bold text-teal-600">
                           {{ $restockOrder->expected_delivery_date->format('d F Y') }}
                       </span>
                    </p>
                    <p class="mt-4"><span class="font-semibold">Status Saat Ini:</span></p>
                    <span class="inline-block px-6 py-3 rounded-full text-lg font-bold mt-2
                        @if($restockOrder->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($restockOrder->status == 'confirmed_by_supplier') bg-blue-100 text-blue-800
                        @elseif($restockOrder->status == 'in_transit') bg-orange-100 text-orange-800
                        @elseif($restockOrder->status == 'received') bg-green-100 text-green-800 @endif">
                        {{ ucwords(str_replace('_', ' ', $restockOrder->status)) }}
                    </span>
                </div>

                <div class="bg-gray-50 p-8 rounded-2xl">
                    <h3 class="text-xl font-bold text-gray-700 mb-4">Dibuat Oleh</h3>
                    <p class="text-3xl font-bold text-teal-600">{{ $restockOrder->manager->name }}</p>
                    <p class="text-gray-600">{{ $restockOrder->manager->email }}</p>
                </div>
            </div>

            @if(in_array(auth()->user()->role, ['admin', 'manager', 'staff']) &&
               ($restockOrder->status === 'confirmed_by_supplier' || $restockOrder->status === 'in_transit'))
            <div class="bg-green-50 border-4 border-green-500 rounded-3xl p-10 text-center mb-10">
                <form action="{{ route('restock-orders.receive', $restockOrder) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold text-3xl px-16 py-10 rounded-3xl shadow-2xl">
                        TERIMA BARANG SEKARANG
                    </button>
                </form>
            </div>
            @endif

            <div class="bg-gray-50 rounded-2xl p-8"></div>

            @if($restockOrder->supplier_notes)
            <div class="mt-10 bg-blue-50 p-8 rounded-2xl border-l-4 border-blue-500">
                <h4 class="text-xl font-bold text-blue-800 mb-3">Catatan dari Supplier</h4>
                <p class="text-gray-700 text-lg">{{ $restockOrder->supplier_notes }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
