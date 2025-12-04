@extends('layouts.app')

@section('title', 'Dashboard Supplier')
@section('page-title', 'Dashboard Supplier')

@section('content')
<div class="max-w-7xl mx-auto space-y-10 pb-20">

    @php
        $supplierId = auth()->id();
        $totalOrders = \App\Models\RestockOrder::where('supplier_id', $supplierId)->count();
        $pendingOrders = \App\Models\RestockOrder::where('supplier_id', $supplierId)->where('status', 'pending')->count();
        $completedOrders = \App\Models\RestockOrder::where('supplier_id', $supplierId)->whereIn('status', ['received', 'in_transit'])->count();

        $latestPending = \App\Models\RestockOrder::where('supplier_id', $supplierId)
            ->where('status', 'pending')
            ->latest()
            ->take(3)
            ->get();
    @endphp

    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-800 to-slate-900 text-white shadow-2xl p-10">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="bg-indigo-500/30 text-indigo-200 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-indigo-500/50">Partner Portal</span>
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight">
                    Halo, <span class="text-indigo-400">{{ auth()->user()->name }}</span>!
                </h1>
                <p class="mt-4 text-slate-300 max-w-xl text-lg leading-relaxed">
                    Selamat datang di panel mitra Gudang Jaya. Kelola pesanan masuk dan konfirmasi pengiriman Anda dengan mudah di sini.
                </p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/10 p-4 rounded-2xl backdrop-blur-md border border-white/10 text-center">
                    <i class="fa-solid fa-handshake text-4xl text-indigo-400 mb-2"></i>
                    <p class="text-xs font-bold text-slate-300 uppercase">Trusted Supplier</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-6 hover:shadow-lg transition group">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl group-hover:scale-110 transition">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
            <div>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-wider">Total Pesanan</p>
                <p class="text-4xl font-black text-slate-800">{{ $totalOrders }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-6 hover:shadow-lg transition group">
            <div class="w-16 h-16 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center text-3xl group-hover:scale-110 transition relative">
                <i class="fa-solid fa-clock"></i>
                @if($pendingOrders > 0)
                    <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-4 w-4">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                    </span>
                @endif
            </div>
            <div>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-wider">Perlu Konfirmasi</p>
                <p class="text-4xl font-black {{ $pendingOrders > 0 ? 'text-red-500' : 'text-slate-800' }}">{{ $pendingOrders }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-6 hover:shadow-lg transition group">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl group-hover:scale-110 transition">
                <i class="fa-solid fa-check-circle"></i>
            </div>
            <div>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-wider">Selesai / Dikirim</p>
                <p class="text-4xl font-black text-slate-800">{{ $completedOrders }}</p>
            </div>
        </div>
    </div>

    @if($pendingOrders > 0)
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-slate-800 border-l-4 border-red-500 pl-4">
                Pesanan Masuk Terbaru
                <span class="text-red-500 text-sm font-normal ml-2 animate-pulse">(Butuh Respon Segera)</span>
            </h2>
            <a href="{{ route('supplier.restock-orders.index') }}" class="text-indigo-600 font-bold hover:text-indigo-800 text-sm">
                Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4">
            @foreach($latestPending as $order)
            <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:border-red-200 transition flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-6 w-full md:w-auto">
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl">
                        <i class="fa-solid fa-box-open text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">{{ $order->po_number }}</h3>
                        <div class="text-sm text-slate-500 mt-1 flex flex-col md:flex-row gap-1 md:gap-4">
                            <span><i class="fa-regular fa-calendar mr-1"></i> {{ $order->order_date->format('d M Y') }}</span>
                            <span><i class="fa-solid fa-layer-group mr-1"></i> {{ $order->items->count() }} Jenis Barang</span>
                        </div>
                    </div>
                </div>

                <div class="text-center w-full md:w-auto bg-gray-50 px-6 py-3 rounded-xl">
                    <p class="text-xs font-bold text-gray-400 uppercase">Permintaan Kirim</p>
                    <p class="text-lg font-bold text-slate-700">{{ $order->expected_delivery_date->format('d F Y') }}</p>
                </div>

                <div class="w-full md:w-auto">
                    <a href="{{ route('supplier.restock-orders.show', $order) }}"
                       class="block w-full md:inline-block text-center bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition transform hover:scale-105">
                        Review & Konfirmasi
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-emerald-50 border border-emerald-100 rounded-3xl p-10 text-center">
        <div class="inline-flex p-4 bg-emerald-100 text-emerald-600 rounded-full mb-4">
            <i class="fa-solid fa-mug-hot text-4xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-emerald-800">Semua Beres!</h3>
        <p class="text-emerald-600 mt-2">Tidak ada pesanan baru yang perlu dikonfirmasi saat ini.</p>
        <div class="mt-6">
            <a href="{{ route('supplier.restock-orders.index') }}" class="bg-white text-emerald-700 border border-emerald-200 px-6 py-3 rounded-xl font-bold hover:bg-emerald-50 transition">
                Lihat Riwayat Pesanan
            </a>
        </div>
    </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('supplier.restock-orders.index') }}" class="group block bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:border-indigo-200 transition text-center">
            <div class="w-16 h-16 mx-auto bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center text-2xl mb-4 group-hover:bg-indigo-600 group-hover:text-white transition">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Riwayat Pesanan Lengkap</h3>
            <p class="text-sm text-slate-500 mt-2">Lihat semua PO yang pernah masuk, status pengiriman, dan detail barang.</p>
        </a>
    </div>

</div>
@endsection
