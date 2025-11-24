@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto py-10 px-4">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-extrabold text-gray-800">Daftar Transaksi</h1>
        <a href="{{ route('transactions.create') }}"
           class="bg-gradient-to-r from-indigo-600 to-purple-700 text-white px-8 py-4 rounded-xl text-lg font-bold hover:scale-105 transition shadow-xl">
            + Transaksi Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-indigo-600 to-purple-700 text-white">
                <tr>
                    <th class="px-6 py-4 text-left">No Invoice</th>
                    <th class="px-6 py-4 text-left">Tanggal</th>
                    <th class="px-6 py-4 text-left">Tipe</th>
                    <th class="px-6 py-4 text-left">Pelanggan/Supplier</th>
                    <th class="px-6 py-4 text-right">Total</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($transactions as $t)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-indigo-700">{{ $t->transaction_number }}</td>
                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::parse($t->date)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-4 py-2 rounded-full text-xs font-bold
                            {{ $t->type == 'outgoing' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $t->type == 'outgoing' ? 'Penjualan' : 'Restock' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        {{ $t->customer_name ?? ($t->supplier?->name ?? '-') }}
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-green-700">
                        Rp {{ number_format($t->total_amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('transactions.show', $t) }}"
                           class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition">
                            Lihat Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-gray-500 text-xl">
                        Belum ada transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
