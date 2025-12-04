@extends('layouts.app')
@section('title', 'Detail Produk')
@section('page-title', 'Detail Produk')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <a href="{{ route('products.index') }}" class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-medium mb-6 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar
    </a>

    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-t-8 border-indigo-600">
        <div class="grid grid-cols-1 md:grid-cols-12">

            <div class="md:col-span-5 bg-gray-50 p-8 flex items-center justify-center border-r border-gray-100 min-h-[400px]">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="max-h-80 w-auto object-contain drop-shadow-2xl rounded-lg transform hover:scale-105 transition duration-500">
                @else
                    <div class="text-center text-gray-400">
                        <i class="fa-solid fa-image text-8xl opacity-30 mb-4"></i>
                        <p class="text-sm">Tidak ada gambar produk</p>
                    </div>
                @endif
            </div>

            <div class="md:col-span-7 p-8 md:p-10 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </span>

                        @if($product->isLowStock())
                            <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold animate-pulse flex items-center">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Stok Menipis
                            </span>
                        @endif
                    </div>

                    <h1 class="text-4xl font-extrabold text-slate-900 mb-2">{{ $product->name }}</h1>
                    <div class="text-lg font-mono font-bold text-slate-400 mb-6 flex items-center">
                        <i class="fa-solid fa-barcode mr-2"></i> {{ $product->sku }}
                    </div>

                    <p class="text-slate-600 leading-relaxed mb-8 border-l-4 border-indigo-100 pl-4">
                        {{ $product->description ?: 'Tidak ada deskripsi detail untuk produk ini.' }}
                    </p>

                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase">Harga Jual</p>
                            <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase">Stok Fisik</p>
                            <p class="text-2xl font-bold text-slate-800">
                                {{ $product->current_stock }}
                                <span class="text-sm font-medium text-slate-500">{{ $product->unit }}</span>
                            </p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase">Harga Beli</p>
                            <p class="text-xl font-bold text-slate-600">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase">Lokasi Rak</p>
                            <p class="text-xl font-bold text-slate-600">{{ $product->location ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                @if(in_array(auth()->user()->role, ['admin', 'manager']))
                <div class="flex gap-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('products.edit', $product) }}"
                       class="flex-1 bg-amber-50 text-amber-600 text-center py-3 rounded-xl font-bold hover:bg-amber-100 transition border border-amber-200">
                        <i class="fa-solid fa-pen mr-2"></i> Edit Data
                    </a>

                    @if(auth()->user()->role == 'manager' && $product->isLowStock())
                        <a href="{{ route('restock-orders.create', ['product_id' => $product->id]) }}"
                           class="flex-1 bg-indigo-600 text-white text-center py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                            <i class="fa-solid fa-cart-plus mr-2"></i> Restock
                        </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
