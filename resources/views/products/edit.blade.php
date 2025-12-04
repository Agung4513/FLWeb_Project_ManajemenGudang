@extends('layouts.app')
@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <a href="{{ route('products.index') }}" class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-medium mb-6 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Batal & Kembali
    </a>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-indigo-50 px-8 py-6 border-b border-indigo-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-white text-indigo-600 rounded-xl flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Edit Produk</h2>
                <p class="text-indigo-600 text-sm font-medium">{{ $product->name }}</p>
            </div>
        </div>

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                        <select name="category_id" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500 bg-white">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-1 uppercase">SKU (Permanen)</label>
                            <span class="text-lg font-mono font-black text-indigo-600">{{ $product->sku }}</span>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-1 uppercase">Stok Saat Ini</label>
                            <span class="text-lg font-black text-slate-700">{{ $product->current_stock }} <span class="text-xs font-normal">{{ $product->unit }}</span></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="4" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500 focus:outline-none">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Harga Beli</label>
                            <input type="number" name="buy_price" value="{{ old('buy_price', $product->buy_price) }}" required min="0"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Harga Jual</label>
                            <input type="number" name="sell_price" value="{{ old('sell_price', $product->sell_price) }}" required min="0"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Min. Alert Stok</label>
                            <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" required min="0"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Satuan</label>
                            <select name="unit" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium bg-white">
                                @foreach(['pcs', 'box', 'kg', 'liter'] as $u)
                                    <option value="{{ $u }}" {{ $product->unit == $u ? 'selected' : '' }}>{{ ucfirst($u) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Lokasi Rak</label>
                        <input type="text" name="location" value="{{ old('location', $product->location) }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500">
                    </div>

                    <div class="flex gap-4 items-start">
                        @if($product->image)
                            <div class="w-20 h-20 rounded-lg overflow-hidden border border-gray-200 flex-shrink-0">
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="flex-1">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Ganti Gambar</label>
                            <input type="file" name="image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end gap-4">
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg hover:scale-105 transition transform">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
