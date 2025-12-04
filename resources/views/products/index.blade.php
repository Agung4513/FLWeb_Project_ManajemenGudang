@extends('layouts.app')
@section('title', 'Daftar Produk')
@section('page-title', 'Master Data Produk')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-3xl shadow-xl overflow-hidden mb-8 text-white relative">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <i class="fa-solid fa-boxes-stacked text-9xl"></i>
        </div>
        <div class="p-8 md:p-10 relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Manajemen Produk</h1>
                <p class="text-indigo-100 mt-2 text-lg">Kelola stok, harga, dan informasi barang di gudang.</p>
                <div class="mt-4 inline-flex items-center bg-white/20 px-4 py-2 rounded-lg backdrop-blur-sm">
                    <i class="fa-solid fa-box-open mr-2"></i>
                    <span class="font-bold">{{ $products->total() }}</span> <span class="ml-1 text-sm opacity-80">Total Item</span>
                </div>
            </div>
            @if(in_array(auth()->user()->role, ['admin', 'manager']))
            <a href="{{ route('products.create') }}"
               class="group bg-white text-indigo-700 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-indigo-50 transition shadow-lg transform hover:-translate-y-1 flex items-center">
                <i class="fa-solid fa-plus mr-2 group-hover:rotate-90 transition-transform"></i>
                Tambah Produk
            </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-xl shadow-sm mb-6 flex items-center">
            <i class="fa-solid fa-check-circle text-xl mr-3"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-xl shadow-sm mb-6 flex items-center">
            <i class="fa-solid fa-triangle-exclamation text-xl mr-3"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-slate-600 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="py-5 px-6">Produk</th>
                        <th class="py-5 px-6">Kategori</th>
                        <th class="py-5 px-6">Harga</th>
                        <th class="py-5 px-6 text-center">Stok</th>
                        <th class="py-5 px-6">Lokasi</th>
                        <th class="py-5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-indigo-50/30 transition duration-150 group">
                        <td class="py-4 px-6">
                            <div class="flex items-center">
                                <div class="h-14 w-14 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden mr-4 border border-gray-200 relative group-hover:border-indigo-300 transition">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full text-gray-400">
                                            <i class="fa-solid fa-image text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800 text-base group-hover:text-indigo-700 transition">{{ $product->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono bg-slate-100 px-2 py-0.5 rounded inline-block mt-1">{{ $product->sku }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="py-4 px-6">
                            <span class="bg-indigo-50 text-indigo-600 py-1 px-3 rounded-full text-xs font-bold border border-indigo-100">
                                {{ $product->category->name ?? 'Tanpa Kategori' }}
                            </span>
                        </td>

                        <td class="py-4 px-6">
                            <div class="flex flex-col text-sm">
                                <span class="text-slate-400 text-xs">Beli: Rp {{ number_format($product->buy_price, 0, ',', '.') }}</span>
                                <span class="font-bold text-emerald-600">Jual: Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                            </div>
                        </td>

                        <td class="py-4 px-6 text-center">
                            @if($product->isLowStock())
                                <div class="inline-flex flex-col items-center">
                                    <span class="text-2xl font-black text-red-500">{{ $product->current_stock }}</span>
                                    <span class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse border border-red-200">
                                        Low Stock
                                    </span>
                                </div>
                            @else
                                <div class="inline-flex flex-col items-center">
                                    <span class="text-2xl font-black text-slate-700">{{ $product->current_stock }}</span>
                                    <span class="text-xs text-slate-400 font-medium">{{ $product->unit }}</span>
                                </div>
                            @endif
                        </td>

                        <td class="py-4 px-6 text-sm font-medium text-slate-600">
                            @if($product->location)
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-location-dot text-slate-300"></i>
                                    {{ $product->location }}
                                </div>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>

                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <a href="{{ route('products.show', $product) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition" title="Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                @if(in_array(auth()->user()->role, ['admin', 'manager']))
                                    <a href="{{ route('products.edit', $product) }}" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus produk {{ $product->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class="md:hidden flex justify-end gap-3 text-lg">
                                <a href="{{ route('products.show', $product) }}" class="text-blue-500"><i class="fa-solid fa-eye"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-16">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="bg-slate-50 p-4 rounded-full mb-3">
                                    <i class="fa-solid fa-box-open text-4xl text-slate-300"></i>
                                </div>
                                <p class="text-lg font-medium text-slate-500">Belum ada data produk.</p>
                                @if(in_array(auth()->user()->role, ['admin', 'manager']))
                                <p class="text-sm mt-1">Silakan tambahkan produk baru.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
