@extends('layouts.app')
@section('title', 'Daftar Restock Order')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-6">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-purple-700 to-pink-700 text-white p-12 text-center">
            <h1 class="text-6xl font-bold">DAFTAR PURCHASE ORDER</h1>
            <p class="text-3xl mt-4">GUDANG JAYA</p>
        </div>

        <div class="p-10">
            @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full bg-white rounded-xl shadow-lg">
                    <thead class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                        <tr>
                            <th class="px-8 py-6 text-left">No PO</th>
                            <th class="px-8 py-6 text-left">Tanggal Order</th>
                            <th class="px-8 py-6 text-left">Estimasi Tiba</th>
                            <th class="px-8 py-6 text-center">Status</th>
                            <th class="px-8 py-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-8 py-6 font-bold text-purple-700 text-xl">{{ $order->po_number }}</td>
                            <td class="px-8 py-6">{{ $order->order_date->format('d/m/Y') }}</td>
                            <td class="px-8 py-6 text-orange-600 font-bold">
                                {{ $order->expected_delivery_date->format('d/m/Y') }}
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-6 py-3 rounded-full text-lg font-bold
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'confirmed_by_supplier') bg-blue-100 text-blue-800 @endif">
                                    {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <a href="{{ route('supplier.restock-orders.show', $order) }}"
                                   class="bg-gradient-to-r from-indigo-600 to-purple-700 text-white px-10 py-5 rounded-xl font-bold text-xl shadow-lg hover:scale-105 transition">
                                    LIHAT DETAIL
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-10">{{ $orders->links() }}</div>
            @else
            <div class="text-center py-32 text-gray-500 text-4xl font-bold">
                Belum ada Purchase Order untuk Anda
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
