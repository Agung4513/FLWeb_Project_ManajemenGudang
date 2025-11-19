@extends('layouts.app')
@section('page-title', 'Edit Produk: ' . $product->name)

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-700 p-10 text-white">
            <h1 class="text-4xl font-extrabold">Edit Produk</h1>
            <p class="text-xl opacity-90 mt-3">Kode SKU: <span class="font-mono text-2xl">{{ $product->sku }}</span> (Tidak dapat diubah)</p>
        </div>

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-10">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div>
                    <label class="block text-lg font-bold text-gray-700 mb-3">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full border-2 border-gray-300 rounded-xl px-5 py-4 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition">
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-lg font-bold text-gray-700 mb-3">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full border-2 border-gray-300 rounded-xl px-5 py-4 focus:border-indigo-600">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2 bg-gradient-to-r from-indigo-50 to-purple-50 p-8 rounded-2xl border-4 border-indigo-200">
                    <label class="block text-xl font-bold text-indigo-800 mb-4">Kode SKU (Tetap)</label>
                    <div class="text-5xl font-mono font-black text-indigo-700 tracking-widest">
                        {{ $product->sku }}
                    </div>
                    <p class="text-indigo-600 mt-4 text-lg">Kode unik ini tidak dapat diubah.</p>
                </div>

                <div>
                    <label class="block text-lg font-bold text-gray-700 mb-3">Stok Saat Ini <span class="text-red-500">*</span></label>
                    <input type="number" name="current_stock" value="{{ old('current_stock', $product->current_stock) }}" required min="0"
                           class="w-full border-2 border-gray-300 rounded-xl px-5 py-4">
                    @error('current_stock') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-lg font-bold text-gray-700 mb-3">Minimum Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" required min="1"
                           class="w-full border-2 border-gray-300 rounded-xl px-5 py-4">
                    @error('min_stock') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-lg font-bold text-gray-700 mb-3">Satuan <span class="text-red-500">*</span></label>
                    <select name="unit" required class="w-full border-2 border-gray-300 rounded-xl px-5 py-4">
                        @foreach(['pcs', 'box', 'kg', 'liter', 'botol', 'sachet'] as $u)
                            <option value="{{ $u }}" {{ old('unit', $product->unit) == $u ? 'selected' : '' }}>
                                {{ ucfirst($u) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-lg font-bold text-gray-700 mb-3">Lokasi Gudang</label>
                    <input type="text" name="location" value="{{ old('location', $product->location) }}"
                           class="w-full border-2 border-gray-300 rounded-xl px-5 py-4" placeholder="Contoh: Rak A-12">
                </div>

                <div>
                    <label class="block text-lg font-bold text-gray-700 mb-3">Harga Beli (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="buy_price" value="{{ old('buy_price', $product->buy_price) }}" required min="1"
                           class="w-full border-2 border-gray-300 rounded-xl px-5 py-4">
                    @error('buy_price') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-lg font-bold text-gray-700 mb-3">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="sell_price" value="{{ old('sell_price', $product->sell_price) }}" required min="1"
                           class="w-full border-2 border-gray-300 rounded-xl px-5 py-4">
                    @error('sell_price') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-lg font-bold text-gray-700 mb-3">Gambar Produk Saat Ini</label>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-64 h-64 object-cover rounded-xl shadow-lg">
                    @else
                        <p class="text-gray-500">Belum ada gambar</p>
                    @endif
                    <input type="file" name="image" accept="image/*" class="mt-4 w-full">
                    <p class="text-sm text-gray-500">Biarkan kosong jika tidak ingin ganti gambar</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-lg font-bold text-gray-700 mb-3">Deskripsi Produk</label>
                    <textarea name="description" rows="6" class="w-full border-2 border-gray-300 rounded-xl px-5 py-4">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <div class="mt-12 flex justify-end gap-6">
                <a href="{{ route('products.index') }}"
                   class="px-10 py-5 bg-gray-600 text-white rounded-xl font-bold text-xl hover:bg-gray-700 transition transform hover:scale-105">
                    Batal
                </a>
                <button type="submit"
                        class="px-12 py-5 bg-gradient-to-r from-green-600 to-emerald-700 text-white rounded-xl font-bold text-xl hover:from-green-700 hover:to-emerald-800 transition shadow-2xl transform hover:scale-110">
                    Update Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
