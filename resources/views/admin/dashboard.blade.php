@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 pb-20">

    @php
        $totalUsers = \App\Models\User::count();
        $pendingUsers = \App\Models\User::where('is_active', false)->count();
        $totalProducts = \App\Models\Product::count();
        $lowStockCount = \App\Models\Product::whereRaw('current_stock <= min_stock')->count();

        $revenue = \App\Models\Transaction::where('type', 'outgoing')
            ->where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');

        $recentTrx = \App\Models\Transaction::with('user')->latest()->take(5)->get();
    @endphp

    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-800 to-slate-900 px-8 py-10 text-white shadow-2xl">
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs font-bold uppercase tracking-wider mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> System Online
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight md:text-5xl">
                    Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">{{ auth()->user()->name }}</span>
                </h1>
                <p class="mt-2 text-slate-300 text-lg max-w-2xl">
                    Pusat kendali sistem. Pantau performa gudang, kelola pengguna, dan audit laporan keuangan dari sini.
                </p>
            </div>
            <div class="hidden md:block opacity-20 transform rotate-12">
                <i class="fa-solid fa-chess-king text-9xl text-white"></i>
            </div>
        </div>

        <div class="absolute top-0 right-0 -mt-16 -mr-16 h-80 w-80 rounded-full bg-purple-600/30 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-16 -ml-16 h-64 w-64 rounded-full bg-blue-600/20 blur-3xl"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-lg transition group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pengguna</p>
                    <p class="mt-2 text-3xl font-black text-slate-800">{{ $totalUsers }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl group-hover:scale-110 transition">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            @if($pendingUsers > 0)
                <a href="{{ route('admin.users.index') }}" class="mt-4 inline-flex items-center text-xs font-bold text-red-500 bg-red-50 px-2 py-1 rounded hover:bg-red-100 transition">
                    {{ $pendingUsers }} Menunggu Approval <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            @else
                <p class="mt-4 text-xs text-emerald-600 font-bold flex items-center"><i class="fa-solid fa-check mr-1"></i> Semua Akun Aktif</p>
            @endif
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-lg transition group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Omset (Bulan Ini)</p>
                    <p class="mt-2 text-2xl font-black text-emerald-600 truncate" title="Rp {{ number_format($revenue, 0, ',', '.') }}">
                        Rp {{ number_format($revenue / 1000000, 1, ',', '.') }}<span class="text-sm text-slate-400 font-normal">Jt</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
            </div>
            <p class="mt-4 text-xs text-slate-400">Total nilai barang keluar (Approved)</p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-lg transition group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Database Produk</p>
                    <p class="mt-2 text-3xl font-black text-slate-800">{{ $totalProducts }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 transition">
                    <i class="fa-solid fa-box-archive"></i>
                </div>
            </div>
            <p class="mt-4 text-xs text-slate-400">Jenis SKU terdaftar</p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-lg transition group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Stok Kritis</p>
                    <p class="mt-2 text-3xl font-black {{ $lowStockCount > 0 ? 'text-red-600' : 'text-slate-800' }}">{{ $lowStockCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl group-hover:scale-110 transition">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            @if($lowStockCount > 0)
                <p class="mt-4 text-xs text-red-500 font-bold">Perlu Restock Segera</p>
            @else
                <p class="mt-4 text-xs text-emerald-600 font-bold">Stok Aman</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="space-y-6">
            <h3 class="text-lg font-bold text-slate-700 border-l-4 border-indigo-500 pl-3">Aksi Cepat</h3>

            <a href="{{ route('admin.users.index') }}" class="group flex items-center p-4 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-purple-200 transition">
                <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl mr-4 group-hover:bg-purple-600 group-hover:text-white transition">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800">Manajemen User</h4>
                    <p class="text-xs text-slate-500">Approve Supplier & Staff</p>
                </div>
                <i class="fa-solid fa-chevron-right ml-auto text-slate-300 group-hover:text-purple-500"></i>
            </a>

            <a href="{{ route('categories.index') }}" class="group flex items-center p-4 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-200 transition">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl mr-4 group-hover:bg-blue-600 group-hover:text-white transition">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800">Master Kategori</h4>
                    <p class="text-xs text-slate-500">Kelola Pengelompokan</p>
                </div>
                <i class="fa-solid fa-chevron-right ml-auto text-slate-300 group-hover:text-blue-500"></i>
            </a>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-slate-700">Aktivitas Gudang Terkini</h3>
                    <a href="{{ route('admin.transactions.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">User</th>
                                <th class="px-6 py-4">Tipe</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentTrx as $trx)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-mono text-xs font-bold text-slate-600">{{ $trx->transaction_number }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold mr-2">
                                            {{ substr($trx->user->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-medium text-slate-700">{{ $trx->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($trx->type == 'incoming')
                                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded border border-blue-100">MASUK</span>
                                    @else
                                        <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded border border-red-100">KELUAR</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($trx->status == 'pending')
                                        <span class="w-2 h-2 rounded-full bg-orange-500 inline-block mr-1"></span> <span class="text-xs text-orange-600 font-bold">Pending</span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block mr-1"></span> <span class="text-xs text-emerald-600 font-bold">Selesai</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-xs text-slate-400">
                                    {{ $trx->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada aktivitas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
