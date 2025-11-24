@extends('layouts.app')
@section('page-title', 'Daftar Restock Order')
@section('title', 'Restock Order')

@section('content')
<div class="max-w-7xl mx-auto py-10">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-purple-700 to-indigo-800 text-white p-10">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-5xl font-bold">Daftar Restock Order</h1>
                    <p class="text-xl mt-3 opacity-90">Kelola semua purchase order ke supplier</p>
                </div>
                @if(in_array(auth()->user()->role, ['admin', 'manager']))
                <a href="{{ route('restock-orders.create') }}"
                   class="bg-green-500 hover:bg-green-600 text-white font-bold text-xl px-10 py-6 rounded-2xl shadow-lg transform hover:scale-105 transition flex items-center gap-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Restock Baru
                </a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-6 mx-10 mt-8 rounded-r-xl text-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="p-10">
            @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white">
                        <tr>
                            <th class="px-8 py-6 text-left rounded-tl-2xl">No PO</th>
                            <th class="px-8 py-6 text-left">Supplier</th>
                            <th class="px-8 py-6 text-left">Tanggal</th>
                            <th class="px-8 py-6 text-left">Estimasi Tiba</th>
                            <th class="px-8 py-6 text-center">Status</th>
                            <th class="px-8 py-6 text-center rounded-tr-2xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-8 py-6 font-bold text-purple-700 text-xl">{{ $order->po_number }}</td>
                            <td class="px-8 py-6 font-semibold">{{ $order->supplier->name }}</td>
                            <td class="px-8 py-6">{{ $order->order_date->format('d/m/Y') }}</td>
                            <td class="px-8 py-6 text-orange-600 font-bold">
                                {{ $order->expected_delivery_date->format('d/m/Y') }}
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-6 py-3 rounded-full text-lg font-bold
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'confirmed_by_supplier') bg-blue-100 text-blue-800
                                    @elseif($order->status == 'in_transit') bg-orange-100 text-orange-800
                                    @elseif($order->status == 'received') bg-green-100 text-green-800 @endif">
                                    {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center space-x-4">
                                <a href="{{ route('restock-orders.show', $order) }}"
                                   class="bg-indigo-600 text-white px-8 py-4 rounded-xl hover:bg-indigo-700 font-bold text-lg shadow-lg">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-10">{{ $orders->links() }}</div>
            @else
            <div class="text-center py-32 text-gray-500 text-3xl">
                Belum ada Restock Order
            </div>
            @endif
        </div>
    </div>
</div>

<div id="receive-modal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50"></div>

@push('scripts')
<script>
function openReceiveModal(poNumber, url) {
    document.getElementById('modal-po').textContent = poNumber;
    document.getElementById('receive-form').action = url;
    document.getElementById('receive-modal').classList.remove('hidden');
    document.getElementById('receive-modal').classList.add('flex');
}
function closeModal() {
    document.getElementById('receive-modal').classList.add('hidden');
    document.getElementById('receive-modal').classList.remove('flex');
}
</script>
@endpush
@endsection
