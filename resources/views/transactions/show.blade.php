@extends('layouts.app')

@section('title', 'Detail Transaksi ' . $transaction->transaction_number)
@section('page-title', 'Detail Transaksi')

@section('content')
<div class="max-w-5xl mx-auto py-8">

    @php
        $backRoute = auth()->user()->role == 'manager'
            ? route('manager.transactions.index')
            : (auth()->user()->role == 'admin' ? route('admin.transactions.index') : route('staff.transactions.index'));
    @endphp

    <a href="{{ $backRoute }}" class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-medium mb-6 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Riwayat
    </a>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">->
        <div class="px-8 py-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center bg-slate-50">
            <div>
                <h1 class="text-2xl font-black text-slate-800">{{ $transaction->transaction_number }}</h1>
                <p class="text-slate-500 text-sm mt-1">
                    Dibuat oleh <span class="font-bold text-slate-700">{{ $transaction->user->name ?? 'User Terhapus' }}</span>
                    pada {{ \Carbon\Carbon::parse($transaction->date)->format('d F Y') }}
                </p>
                @if($transaction->restock_order_id)
                    <div class="mt-2 inline-flex items-center bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg text-xs font-bold border border-indigo-200">
                        <i class="fa-solid fa-link mr-2"></i> Terhubung dengan PO: {{ $transaction->restockOrder->po_number ?? 'PO Terhapus' }}
                    </div>
                @endif
            </div>
            <div class="mt-4 md:mt-0">
                @if($transaction->status === 'pending')
                    <span class="px-4 py-2 rounded-xl bg-orange-100 text-orange-700 font-bold border border-orange-200 flex items-center shadow-sm">
                        <span class="w-2.5 h-2.5 bg-orange-500 rounded-full mr-2 animate-pulse"></span> Menunggu Approval
                    </span>
                @elseif($transaction->status === 'approved')
                    <span class="px-4 py-2 rounded-xl bg-emerald-100 text-emerald-700 font-bold border border-emerald-200 flex items-center shadow-sm">
                        <i class="fa-solid fa-check-circle mr-2"></i> Disetujui
                    </span>
                @else
                    <span class="px-4 py-2 rounded-xl bg-red-100 text-red-700 font-bold border border-red-200 flex items-center shadow-sm">
                        <i class="fa-solid fa-xmark-circle mr-2"></i> Ditolak
                    </span>
                @endif
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-100">
                <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-1">Tipe Transaksi</p>
                <p class="text-xl font-black {{ $transaction->type == 'incoming' ? 'text-blue-600' : 'text-red-600' }}">
                    {{ $transaction->type == 'incoming' ? 'Barang Masuk' : 'Barang Keluar' }}
                </p>
            </div>

            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Pihak Terkait</p>
                <p class="text-xl font-bold text-slate-800">
                    {{ $transaction->customer_name ?? ($transaction->supplier->name ?? '-') }}
                </p>
            </div>

            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Nilai</p>
                <p class="text-xl font-black text-slate-800">
                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="px-8 pb-8">
            <h3 class="text-lg font-bold text-slate-700 mb-4 border-l-4 border-indigo-500 pl-3">Rincian Barang</h3>
            <div class="overflow-hidden rounded-xl border border-gray-200">
                <table class="w-full text-left">
                    <thead class="bg-gray-100 text-gray-600 font-bold text-sm uppercase">
                        <tr>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4 text-center">Jumlah</th>
                            <th class="px-6 py-4 text-right">Harga Satuan</th>
                            <th class="px-6 py-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($transaction->items as $item)
                        <tr>
                            <td class="px-6 py-4">
                                @if($item->product)
                                    <div class="font-bold text-slate-800">
                                        {{ $item->product->name }}
                                        @if(method_exists($item->product, 'trashed') && $item->product->trashed())
                                            <span class="ml-2 px-2 py-0.5 text-[10px] bg-red-100 text-red-600 rounded border border-red-200">Terhapus</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $item->product->sku }}</div>
                                @else
                                    <div class="font-bold text-red-500 italic">
                                        <i class="fa-solid fa-ban mr-1"></i> Produk Telah Dihapus
                                    </div>
                                    <div class="text-xs text-red-300">Data Master Hilang</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-700">
                                {{ $item->quantity }} {{ $item->product->unit ?? 'Unit' }}
                            </td>
                            <td class="px-6 py-4 text-right text-slate-600">
                                Rp {{ number_format($item->price_at_transaction, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-600">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($transaction->notes)
        <div class="px-8 pb-8">
            <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100 text-yellow-800 text-sm">
                <span class="font-bold block mb-1"><i class="fa-solid fa-note-sticky mr-1"></i> Catatan:</span>
                {{ $transaction->notes }}
            </div>
        </div>
        @endif

        @if(auth()->user()->role === 'manager' && $transaction->status === 'pending')
        <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex justify-end gap-4">
            <form action="{{ route('manager.transactions.reject', $transaction) }}" method="POST" onsubmit="return confirm('Tolak transaksi? Data akan dihapus.');">
                @csrf @method('PATCH')
                <button class="px-6 py-3 bg-white border border-red-200 text-red-600 font-bold rounded-xl hover:bg-red-50 transition">
                    <i class="fa-solid fa-xmark mr-2"></i> Tolak
                </button>
            </form>

            <form action="{{ route('manager.transactions.approve', $transaction) }}" method="POST" onsubmit="return confirm('Setujui transaksi ini?');">
                @csrf @method('PATCH')
                <button class="px-8 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition transform hover:scale-105">
                    <i class="fa-solid fa-check mr-2"></i> Setujui Transaksi
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
