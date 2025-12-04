@extends('layouts.app')
@section('title', 'Restock Order')
@section('page-title', 'Restock Order')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-3xl shadow-xl overflow-hidden mb-8 text-white relative">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <i class="fa-solid fa-truck-ramp-box text-9xl"></i>
        </div>
        <div class="p-8 md:p-10 relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Purchase Orders (PO)</h1>
                <p class="text-amber-100 mt-2 text-lg">Kelola pengadaan barang dari Supplier.</p>
            </div>

            @if(auth()->user()->role === 'manager')
            <a href="{{ route('restock-orders.create') }}"
               class="group bg-white text-orange-600 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-orange-50 transition shadow-lg transform hover:-translate-y-1 flex items-center">
                <i class="fa-solid fa-plus mr-2 group-hover:rotate-90 transition-transform"></i>
                Buat PO Baru
            </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-xl shadow-sm flex items-center">
            <i class="fa-solid fa-check-circle text-xl mr-3"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="py-5 px-6">No. PO</th>
                        <th class="py-5 px-6">Supplier</th>
                        <th class="py-5 px-6">Tgl Order</th>
                        <th class="py-5 px-6">Estimasi Tiba</th>
                        <th class="py-5 px-6 text-center">Status</th>
                        <th class="py-5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-amber-50/30 transition duration-150 group">
                        <td class="py-4 px-6">
                            <div class="font-black text-slate-800 text-base">{{ $order->po_number }}</div>
                            <div class="text-xs text-slate-400 mt-1">
                                {{ $order->items->count() }} Item Barang
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-700">{{ $order->supplier->name }}</div>
                        </td>
                        <td class="py-4 px-6 text-sm text-slate-600">
                            {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                        </td>
                        <td class="py-4 px-6 text-sm font-bold text-orange-600">
                            {{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('d M Y') }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    'confirmed_by_supplier' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'in_transit' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                    'received' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                ];
                                $label = ucwords(str_replace('_', ' ', $order->status));
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            @php
                                $route = auth()->user()->role == 'supplier'
                                    ? route('supplier.restock-orders.show', $order)
                                    : route('restock-orders.show', $order);
                            @endphp
                            <a href="{{ $route }}" class="bg-slate-100 text-slate-600 hover:bg-indigo-600 hover:text-white px-4 py-2 rounded-lg font-bold text-sm transition">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-16 text-slate-400">
                            <i class="fa-solid fa-box-open text-4xl mb-2"></i>
                            <p>Belum ada Purchase Order.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-gray-100">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
