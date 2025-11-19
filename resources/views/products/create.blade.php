@extends('layouts.app')
@section('page-title', 'Tambah Produk Baru')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-700 p-8 text-white">
            <h1 class="text-3xl font-bold">Tambah Produk Baru</h1>
            <p class="opacity-90 mt-2">Isi data produk dengan lengkap. Kode SKU akan dibuat otomatis.</p>
        </div>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div>
                    <label class="block font-semibold text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border-2 rounded-xl px-5 py-4 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition"
                           placeholder="Contoh: Laptop ASUS ROG">
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full border-2 rounded-xl px-5 py-4 focus:border-indigo-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2 bg-gradient-to-r from-indigo-50 to-purple-50 p-8 rounded-2xl border-4 border-indigo-200">
                    <label class="block text-xl font-bold text-indigo-800 mb-4">Kode SKU (Dibuat Otomatis)</label>
                    <div class="text-5xl font-mono font-black text-indigo-700 tracking-widest">
                        {{ $previewSku ?? 'PRD000001' }}
                    </div>
                    <p class="text-indigo-600 mt-4 text-lg">
                        Kode unik ini dibuat otomatis oleh sistem dan <strong>tidak dapat diubah</strong>.
                    </p>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-2">Stok Awal <span class="text-red-500">*</span></label>
                    <input type="number" name="current_stock" value="{{ old('current_stock', 0) }}" required min="0"
                           class="w-full border-2 rounded-xl px-5 py-4" placeholder="0">
                    @error('current_stock') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-2">Minimum Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="min_stock" value="{{ old('min_stock', 10) }}" required min="1"
                           class="w-full border-2 rounded-xl px-5 py-4" placeholder="10">
                    @error('min_stock') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-2">Satuan <span class="text-red-500">*</span></label>
                    <select name="unit" required class="w-full border-2 rounded-xl px-5 py-4">
                        <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                        <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kg</option>
                        <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-2">Lokasi Gudang</label>
                    <input type="text" name="location" value="{{ old('location') }}"
                           class="w-full border-2 rounded-xl px-5 py-4" placeholder="Contoh: Rak A-12">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-2">Harga Beli (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="buy_price" value="{{ old('buy_price') }}" required min="0"
                           class="w-full border-2 rounded-xl px-5 py-4" placeholder="1500000">
                    @error('buy_price') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-2">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="sell_price" value="{{ old('sell_price') }}" required min="0"
                           class="w-full border-2 rounded-xl px-5 py-4" placeholder="2500000">
                    @error('sell_price') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-2">Gambar Produk</label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full border-2 border-dashed rounded-xl px-5 py-8 text-center cursor-pointer hover:border-indigo-500 transition">
                    <p class="text-sm text-gray-500 mt-2">Format: JPG, PNG (maks 2MB)</p>
                    @error('image') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-2">Deskripsi Produk</label>
                    <textarea name="description" rows="5" class="w-full border-2 rounded-xl px-5 py-4"
                              placeholder="Jelaskan spesifikasi produk...">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mt-10 flex gap-4 justify-end">
                <a href="{{ route('products.index') }}"
                   class="px-8 py-4 bg-gray-500 text-white rounded-xl font-bold hover:bg-gray-600 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-10 py-4 bg-gradient-to-r from-indigo-600 to-purple-700 text-white rounded-xl font-bold hover:from-indigo-700 hover:to-purple-800 transition shadow-xl transform hover:scale-105">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
