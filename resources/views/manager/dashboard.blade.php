@extends('layouts.app')

@section('title', 'Dashboard Manager')
@section('page-title', 'Dashboard Manager')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 to-indigo-900 text-white shadow-2xl">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="relative z-10 p-10 md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight md:text-5xl">
                    Halo, <span class="text-yellow-400">{{ auth()->user()->name }}</span>!
                </h1>
                <p class="mt-4 max-w-xl text-lg text-indigo-200">
                    Selamat datang di pusat kendali operasional. Pantau stok, setujui transaksi, dan kelola restock dari sini.
                </p>
            </div>
            <div class="mt-6 md:mt-0">
                <div class="inline-flex items-center rounded-2xl bg-white/10 px-6 py-3 backdrop-blur-md border border-white/20">
                    <i class="fa-solid fa-user-tie text-3xl text-yellow-400 mr-4"></i>
                    <div class="text-left">
                        <p class="text-xs font-bold uppercase text-indigo-300">Posisi</p>
                        <p class="font-bold">Warehouse Manager</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $pendingCount = \App\Models\Transaction::where('status', 'pending')->count();
    @endphp

    @if($pendingCount > 0)
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-red-500 to-orange-600 p-1 shadow-lg animate-pulse">
            <div class="flex flex-col md:flex-row items-center justify-between bg-white/10 backdrop-blur-sm p-6 rounded-xl">
                <div class="flex items-center text-white mb-4 md:mb-0">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white text-red-600 shadow-md">
                        <i class="fa-solid fa-bell text-3xl"></i>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-3xl font-black">{{ $pendingCount }} Transaksi Pending</h2>
                        <p class="text-red-100 font-medium">Menunggu persetujuan Anda segera!</p>
                    </div>
                </div>
                <a href="{{ route('manager.transactions.index') }}"
                   class="group flex items-center rounded-xl bg-white px-8 py-3 font-bold text-red-600 shadow-lg transition hover:bg-gray-50 hover:scale-105">
                    Proses Sekarang
                    <i class="fa-solid fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>
    @else
        <div class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 p-6 text-white shadow-lg flex items-center">
            <div class="mr-6 flex h-14 w-14 items-center justify-center rounded-full bg-white/20 text-2xl">
                <i class="fa-solid fa-check"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold">Semua Aman!</h3>
                <p class="text-emerald-100">Tidak ada transaksi yang menunggu persetujuan saat ini.</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="group relative overflow-hidden rounded-3xl bg-white p-8 shadow-lg border border-gray-100 hover:shadow-xl transition">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-orange-100 transition group-hover:scale-150"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-wider text-gray-500">Transaksi Hari Ini</p>
                        <p class="mt-2 text-5xl font-black text-slate-800">
                            {{ \App\Models\Transaction::whereDate('created_at', today())->count() }}
                        </p>
                    </div>
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-50 text-orange-500">
                        <i class="fa-solid fa-calendar-day text-3xl"></i>
                    </div>
                </div>
                <div class="mt-4 h-1 w-full rounded-full bg-gray-100">
                    <div class="h-1 rounded-full bg-orange-500" style="width: 40%"></div>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-3xl bg-white p-8 shadow-lg border border-gray-100 hover:shadow-xl transition">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-blue-100 transition group-hover:scale-150"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-wider text-gray-500">PO Aktif</p>
                        <p class="mt-2 text-5xl font-black text-slate-800">
                            {{ \App\Models\RestockOrder::whereIn('status', ['pending', 'confirmed_by_supplier'])->count() }}
                        </p>
                    </div>
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-500">
                        <i class="fa-solid fa-truck-fast text-3xl"></i>
                    </div>
                </div>
                <div class="mt-4 h-1 w-full rounded-full bg-gray-100">
                    <div class="h-1 rounded-full bg-blue-500" style="width: 65%"></div>
                </div>
            </div>
        </div>

        @php
            $lowStock = \App\Models\Product::whereRaw('current_stock <= min_stock')->count();
        @endphp
        <div class="group relative overflow-hidden rounded-3xl bg-white p-8 shadow-lg border border-gray-100 hover:shadow-xl transition">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full {{ $lowStock > 0 ? 'bg-red-100' : 'bg-green-100' }} transition group-hover:scale-150"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-wider text-gray-500">Stok Menipis</p>
                        <p class="mt-2 text-5xl font-black {{ $lowStock > 0 ? 'text-red-600' : 'text-slate-800' }}">
                            {{ $lowStock }}
                        </p>
                    </div>
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl {{ $lowStock > 0 ? 'bg-red-50 text-red-500' : 'bg-green-50 text-green-500' }}">
                        <i class="fa-solid {{ $lowStock > 0 ? 'fa-triangle-exclamation' : 'fa-thumbs-up' }} text-3xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    @if($lowStock > 0)
                        <span class="text-xs font-bold text-red-500">Perlu Restock Segera!</span>
                    @else
                        <span class="text-xs font-bold text-green-500">Stok Aman Terkendali</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <h3 class="text-xl font-bold text-slate-700 border-l-4 border-indigo-500 pl-4">Aksi Cepat</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <a href="{{ route('manager.transactions.index') }}"
           class="group flex flex-col items-center justify-center rounded-3xl bg-white p-8 shadow-lg transition hover:-translate-y-1 hover:shadow-2xl border border-gray-100">
            <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-purple-100 text-purple-600 transition group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white">
                <i class="fa-solid fa-file-signature text-3xl"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-800">Approval Center</h4>
            <p class="text-center text-sm text-gray-500 mt-2">Setujui atau tolak transaksi dari staff.</p>
        </a>

        <a href="{{ route('restock-orders.index') }}"
           class="group flex flex-col items-center justify-center rounded-3xl bg-white p-8 shadow-lg transition hover:-translate-y-1 hover:shadow-2xl border border-gray-100">
            <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-amber-100 text-amber-600 transition group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white">
                <i class="fa-solid fa-truck-ramp-box text-3xl"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-800">Buat Restock</h4>
            <p class="text-center text-sm text-gray-500 mt-2">Pesan barang baru ke supplier terdaftar.</p>
        </a>

        <a href="{{ route('products.index') }}"
           class="group flex flex-col items-center justify-center rounded-3xl bg-white p-8 shadow-lg transition hover:-translate-y-1 hover:shadow-2xl border border-gray-100">
            <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 transition group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white">
                <i class="fa-solid fa-boxes-stacked text-3xl"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-800">Kelola Stok</h4>
            <p class="text-center text-sm text-gray-500 mt-2">Lihat, edit, dan pantau stok barang gudang.</p>
        </a>

    </div>
</div>
@endsection
