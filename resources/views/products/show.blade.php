@extends('layouts.app')
@section('page-title', 'Detail Produk: ' . $product->sku)

@section('content')
<div class="max-w-5xl mx-auto py-10">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 p-10 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-extrabold">{{ $product->name }}</h1>
                    <p class="text-xl opacity-90 mt-2">Kode Produk: <span class="font-mono text-2xl">{{ $product->sku }}</span></p>
                </div>
                <div class="text-right">
                    <p class="text-5xl font-black">{{ $product->formatted_sell_price }}</p>
                    <p class="text-sm opacity-80">Harga Jual</p>
                </div>
            </div>
        </div>

        <div class="p-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-2xl p-6 border-2 border-dashed border-gray-300 text-center">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-80 object-cover rounded-xl shadow-lg">
                        @else
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-80 flex items-center justify-center">
                                <div>
                                    <svg class="w-20 h-20 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 mt-4 text-lg">Belum ada gambar</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-8 rounded-2xl border-2 {{ $product->is_low_stock ? 'from-red-50 to-pink-50 border-red-200' : 'border-green-200' }}">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-2xl font-bold {{ $product->is_low_stock ? 'text-red-700' : 'text-green-700' }}">
                                    Stok Saat Ini: {{ $product->current_stock }} {{ $product->unit }}
                                </p>
                                @if($product->is_low_stock)
                                    <p class="text-red-600 font-bold mt-2">STOK RENDAH! Segera restock!</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-lg text-gray-600">Minimum Stok</p>
                                <p class="text-3xl font-black text-gray-800">{{ $product->min_stock }} {{ $product->unit }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wider">Kategori</p>
                            <p class="text-xl font-bold text-indigo-700">{{ $product->category->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wider">Satuan</p>
                            <p class="text-xl font-bold">{{ ucfirst($product->unit) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wider">Harga Beli</p>
                            <p class="text-xl font-semibold text-gray-700">{{ $product->formatted_buy_price }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wider">Lokasi Gudang</p>
                            <p class="text-xl font-medium">{{ $product->location ?? 'Belum ditentukan' }}</p>
                        </div>
                    </div>

                    @if($product->description)
                        <div class="bg-gray-50 p-8 rounded-2xl">
                            <p class="text-sm text-gray-500 uppercase tracking-wider mb-3">Deskripsi Produk</p>
                            <p class="text-gray-700 leading-relaxed text-lg whitespace-pre-line">{{ $product->description }}</p>
                        </div>
                    @endif

                    <div class="flex gap-4 pt-6 border-t">
                        <a href="{{ route('products.edit', $product) }}"
                           class="px-8 py-4 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg transform hover:scale-105">
                            Edit Produk
                        </a>
                        <a href="{{ route('products.index') }}"
                           class="px-8 py-4 bg-gray-600 text-white rounded-xl font-bold hover:bg-gray-700 transition">
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
