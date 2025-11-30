@extends('layouts.app')
@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk Baru')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <a href="{{ route('products.index') }}" class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-medium mb-6 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar
    </a>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-indigo-50 to-white px-8 py-6 border-b border-gray-100">
            <h2 class="text-2xl font-extrabold text-slate-800">Formulir Produk</h2>
            <p class="text-slate-500 text-sm mt-1">Lengkapi data produk baru untuk inventaris.</p>
        </div>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-wider border-b border-indigo-100 pb-2 mb-4">Informasi Dasar</h3>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500 focus:ring focus:ring-indigo-100 focus:outline-none transition"
                               placeholder="Contoh: iPhone 15 Pro Max">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="category_id" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500 focus:outline-none appearance-none bg-white">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">SKU (Auto-Generated)</label>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-mono font-black text-indigo-600 tracking-wider">{{ $previewSku }}</span>
                            <span class="bg-indigo-100 text-indigo-600 text-xs px-2 py-1 rounded font-bold">AUTO</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-2">Kode unik produk akan dibuat otomatis oleh sistem.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="4"
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500 focus:outline-none transition"
                                  placeholder="Spesifikasi produk...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-sm font-bold text-emerald-600 uppercase tracking-wider border-b border-emerald-100 pb-2 mb-4">Harga & Stok</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Harga Beli</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-slate-400 text-sm font-bold">Rp</span>
                                <input type="number" name="buy_price" value="{{ old('buy_price') }}" required min="0"
                                       class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500 focus:outline-none transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Harga Jual</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-slate-400 text-sm font-bold">Rp</span>
                                <input type="number" name="sell_price" value="{{ old('sell_price') }}" required min="0"
                                       class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500 focus:outline-none transition">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Stok Awal</label>
                            <input type="number" name="current_stock" value="{{ old('current_stock', 0) }}" required min="0"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Min. Alert</label>
                            <input type="number" name="min_stock" value="{{ old('min_stock', 10) }}" required min="0"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Satuan</label>
                            <select name="unit" class="w-full px-2 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500 focus:outline-none bg-white">
                                <option value="pcs">Pcs</option>
                                <option value="box">Box</option>
                                <option value="kg">Kg</option>
                                <option value="liter">Liter</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Lokasi Rak</label>
                        <input type="text" name="location" value="{{ old('location') }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium focus:border-indigo-500 focus:outline-none transition"
                               placeholder="Contoh: Rak A-05, Gudang B">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Foto Produk</label>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-indigo-50 hover:border-indigo-300 transition">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500"><span class="font-bold">Klik upload</span> atau drag file</p>
                                <p class="text-xs text-gray-400">PNG, JPG (Max. 2MB)</p>
                            </div>
                            <input type="file" name="image" class="hidden" />
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end gap-4">
                <a href="{{ route('products.index') }}" class="px-6 py-3.5 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition">
                    Batal
                </a>
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg hover:shadow-indigo-500/30 hover:scale-105 transition transform">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
