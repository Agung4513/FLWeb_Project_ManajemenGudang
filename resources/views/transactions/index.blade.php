@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    @if(in_array(auth()->user()->role, ['admin', 'manager']))
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase">Total Transaksi</p>
            <p class="text-3xl font-black text-slate-800">{{ $transactions->total() }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase">Menunggu Approval</p>
            <p class="text-3xl font-black text-orange-500">
                {{ \App\Models\Transaction::where('status', 'pending')->count() }}
            </p>
        </div>
    </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <h2 class="text-2xl font-bold text-slate-800">Daftar Logistik</h2>

        <div class="flex gap-3">
            @if(auth()->user()->role === 'staff')
                <a href="{{ route('staff.transactions.create') }}" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition flex items-center shadow-lg shadow-indigo-500/30">
                    <i class="fa-solid fa-plus mr-2"></i> Transaksi Baru
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-xl shadow-sm flex items-center">
            <i class="fa-solid fa-check-circle mr-3 text-xl"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl shadow-sm flex items-center">
            <i class="fa-solid fa-triangle-exclamation mr-3 text-xl"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="py-5 px-6">No. TRX</th>
                        <th class="py-5 px-6">Tanggal</th>
                        <th class="py-5 px-6">Tipe</th>
                        <th class="py-5 px-6">Ringkasan Item</th>
                        <th class="py-5 px-6">Status</th>
                        <th class="py-5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50/50 transition duration-150 group">
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-800 text-base">{{ $trx->transaction_number }}</div>
                            <div class="text-xs text-slate-400 mt-1 flex items-center">
                                <i class="fa-solid fa-user mr-1"></i> {{ $trx->user->name ?? 'Unknown' }}
                            </div>
                        </td>

                        <td class="py-4 px-6">
                            <span class="text-sm font-medium text-slate-600">
                                {{ \Carbon\Carbon::parse($trx->date)->format('d M Y') }}
                            </span>
                        </td>

                        <td class="py-4 px-6">
                            @if($trx->type === 'incoming')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100">
                                    <i class="fa-solid fa-arrow-down mr-1.5"></i> Masuk
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                                    <i class="fa-solid fa-arrow-up mr-1.5"></i> Keluar
                                </span>
                            @endif
                        </td>

                        <td class="py-4 px-6">
                            <div class="text-sm font-bold text-slate-700">
                                {{ $trx->items->sum('quantity') }} Unit
                            </div>
                            <div class="text-xs text-slate-400 truncate max-w-[150px]">
                                @if($trx->items->first())
                                    @if($trx->items->first()->product)
                                        {{ $trx->items->first()->product->name }}

                                        @if(method_exists($trx->items->first()->product, 'trashed') && $trx->items->first()->product->trashed())
                                            <span class="text-red-400 font-bold" title="Produk ini telah dihapus">(Hapus)</span>
                                        @endif
                                    @else
                                        <span class="text-red-400 italic">Item Terhapus</span>
                                    @endif

                                    @if($trx->items->count() > 1)
                                        <span class="text-slate-500">+{{ $trx->items->count() - 1 }} lainnya</span>
                                    @endif
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </div>
                        </td>

                        <td class="py-4 px-6">
                            @if($trx->status === 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-600 border border-orange-200 animate-pulse">
                                    <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span> Pending
                                </span>
                            @elseif(in_array($trx->status, ['approved', 'verified', 'completed']))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-600 border border-emerald-200">
                                    <i class="fa-solid fa-check mr-1.5"></i> Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600 border border-red-200">
                                    <i class="fa-solid fa-xmark mr-1.5"></i> Ditolak
                                </span>
                            @endif
                        </td>

                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @php
                                    $detailRoute = auth()->user()->role == 'manager'
                                        ? 'manager.transactions.show'
                                        : (auth()->user()->role == 'admin' ? 'admin.transactions.show' : 'staff.transactions.show');
                                @endphp
                                <a href="{{ route($detailRoute, $trx) }}"
                                   class="p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 hover:text-slate-800 transition"
                                   title="Lihat Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                @if(auth()->user()->role === 'manager' && $trx->status === 'pending')
                                    <form action="{{ route('manager.transactions.approve', $trx) }}" method="POST" class="inline-block" onsubmit="return confirm('Setujui transaksi ini? Stok akan berubah.');">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="p-2 bg-emerald-100 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition" title="Setujui">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('manager.transactions.reject', $trx) }}" method="POST" class="inline-block" onsubmit="return confirm('Tolak transaksi?');">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition" title="Tolak">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-16">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="bg-slate-50 p-4 rounded-full mb-3">
                                    <i class="fa-solid fa-file-invoice text-4xl text-slate-300"></i>
                                </div>
                                <p class="text-lg font-medium text-slate-500">Belum ada riwayat transaksi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
