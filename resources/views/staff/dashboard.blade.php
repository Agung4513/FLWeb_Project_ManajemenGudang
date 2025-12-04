@extends('layouts.app')

@section('title', 'Dashboard Staff')
@section('page-title', 'Dashboard Staff')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-800 to-teal-900 text-white shadow-2xl">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="relative z-10 p-10 md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight md:text-5xl">
                    Halo, <span class="text-emerald-400">{{ auth()->user()->name }}</span>!
                </h1>
                <p class="mt-4 max-w-xl text-lg text-emerald-100">
                    Selamat bertugas. Pastikan setiap barang masuk dan keluar tercatat dengan akurat.
                </p>
            </div>
            <div class="mt-6 md:mt-0">
                <div class="inline-flex items-center rounded-2xl bg-white/10 px-6 py-3 backdrop-blur-md border border-white/20">
                    <i class="fa-solid fa-id-card text-3xl text-emerald-400 mr-4"></i>
                    <div class="text-left">
                        <p class="text-xs font-bold uppercase text-emerald-300">Role</p>
                        <p class="font-bold">Staff Gudang</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $newArrivals = \App\Models\RestockOrder::where('status', 'received')
            ->doesntHave('transaction')
            ->latest('updated_at')
            ->get();
    @endphp

    @if($newArrivals->count() > 0)
    <div class="rounded-3xl bg-indigo-50 border border-indigo-100 p-6 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <i class="fa-solid fa-bell text-9xl text-indigo-900"></i>
        </div>

        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-4">
                <div class="bg-indigo-600 text-white p-3 rounded-xl shadow-lg shadow-indigo-500/30">
                    <i class="fa-solid fa-truck-ramp-box text-2xl animate-bounce"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-indigo-900">Barang Restock Tiba!</h3>
                    <p class="text-indigo-600 text-sm">Manager sudah terima barang. Klik "Proses" untuk mencatat masuk ke stok.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($newArrivals as $po)
                <div class="bg-white p-4 rounded-xl border-l-4 border-indigo-500 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start">
                            <span class="font-bold text-slate-800 text-lg">{{ $po->po_number }}</span>
                            <span class="text-xs font-bold bg-indigo-100 text-indigo-700 px-2 py-1 rounded">
                                {{ $po->updated_at ? $po->updated_at->diffForHumans() : 'Baru saja' }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-500 mt-1">Supplier: {{ $po->supplier->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-slate-400 mt-2">
                            <i class="fa-solid fa-box mr-1"></i> {{ $po->items->count() }} Jenis Item
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-100 text-right">
                        <a href="{{ route('staff.transactions.create', ['restock_id' => $po->id]) }}"
                           class="text-sm font-bold text-white bg-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-700 transition inline-flex items-center shadow-lg shadow-indigo-500/20">
                            Proses Masuk <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    <h3 class="text-xl font-bold text-slate-700 border-l-4 border-emerald-500 pl-4">Menu Utama</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <a href="{{ route('staff.transactions.create') }}"
           class="group relative overflow-hidden rounded-3xl bg-white p-8 shadow-lg border border-gray-100 hover:shadow-2xl hover:-translate-y-1 transition h-full flex flex-col justify-center items-center text-center">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-teal-50 opacity-0 group-hover:opacity-100 transition duration-500"></div>
            <div class="relative z-10">
                <div class="w-24 h-24 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner group-hover:scale-110 transition">
                    <i class="fa-solid fa-plus text-4xl"></i>
                </div>
                <h2 class="text-2xl font-black text-slate-800 mb-2">Input Transaksi</h2>
                <p class="text-slate-500">Catat barang masuk (dari supplier) atau barang keluar (ke customer).</p>
            </div>
        </a>

        <a href="{{ route('staff.stock') }}"
           class="group relative overflow-hidden rounded-3xl bg-white p-8 shadow-lg border border-gray-100 hover:shadow-2xl hover:-translate-y-1 transition h-full flex flex-col justify-center items-center text-center">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-blue-50 opacity-0 group-hover:opacity-100 transition duration-500"></div>
            <div class="relative z-10">
                <div class="w-24 h-24 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner group-hover:scale-110 transition">
                    <i class="fa-solid fa-magnifying-glass-chart text-4xl"></i>
                </div>
                <h2 class="text-2xl font-black text-slate-800 mb-2">Cek Stok Gudang</h2>
                <p class="text-slate-500">Lihat ketersediaan barang dan lokasi rak penyimpanan saat ini.</p>
            </div>
        </a>

        <a href="{{ route('staff.transactions.index') }}"
           class="group relative overflow-hidden rounded-3xl bg-white p-8 shadow-lg border border-gray-100 hover:shadow-2xl hover:-translate-y-1 transition h-full flex flex-col justify-center items-center text-center">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-50 to-amber-50 opacity-0 group-hover:opacity-100 transition duration-500"></div>
            <div class="relative z-10">
                <div class="w-24 h-24 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner group-hover:scale-110 transition">
                    <i class="fa-solid fa-clock-rotate-left text-4xl"></i>
                </div>
                <h2 class="text-2xl font-black text-slate-800 mb-2">Riwayat Saya</h2>
                <p class="text-slate-500">Pantau status transaksi yang pernah Anda buat (Pending/Approved).</p>
            </div>
        </a>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center hover:shadow-md transition">
            <div class="p-4 bg-blue-50 text-blue-600 rounded-2xl mr-4">
                <i class="fa-solid fa-clipboard-check text-2xl"></i>
            </div>
            <div>
                <p class="text-slate-500 text-sm font-bold uppercase">Transaksi Hari Ini</p>
                <p class="text-3xl font-black text-slate-800">
                    {{ \App\Models\Transaction::where('user_id', auth()->id())->whereDate('created_at', today())->count() }}
                </p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center hover:shadow-md transition">
            <div class="p-4 bg-orange-50 text-orange-600 rounded-2xl mr-4">
                <i class="fa-solid fa-hourglass-half text-2xl"></i>
            </div>
            <div>
                <p class="text-slate-500 text-sm font-bold uppercase">Menunggu Approval</p>
                <p class="text-3xl font-black text-slate-800">
                    {{ \App\Models\Transaction::where('user_id', auth()->id())->where('status', 'pending')->count() }}
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
