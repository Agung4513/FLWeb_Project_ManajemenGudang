@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori Baru')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <a href="{{ route('categories.index') }}" class="inline-flex items-center text-gray-500 hover:text-indigo-600 font-medium mb-6 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar
    </a>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-gray-50 px-8 py-6 border-b border-gray-100">
            <h2 class="text-2xl font-bold text-slate-800">Formulir Kategori</h2>
            <p class="text-gray-500 text-sm mt-1">Isi detail untuk membuat pengelompokan produk baru.</p>
        </div>

        <form action="{{ route('categories.store') }}" method="POST" class="p-8 md:p-10">
            @csrf

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-4 text-gray-400">
                            <i class="fa-solid fa-tag"></i>
                        </span>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-xl font-medium text-slate-700 placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-100 focus:outline-none transition"
                               placeholder="Contoh: Elektronik, Makanan, Pakaian">
                    </div>
                    @error('name') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="4"
                              class="w-full p-4 border-2 border-gray-200 rounded-xl font-medium text-slate-700 placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-100 focus:outline-none transition resize-none"
                              placeholder="Tuliskan keterangan singkat tentang kategori ini...">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-10 pt-6 border-t border-gray-100">
                <a href="{{ route('categories.index') }}" class="px-6 py-3 text-slate-500 font-bold hover:text-slate-700 transition">
                    Batal
                </a>
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3.5 rounded-xl font-bold hover:shadow-lg hover:scale-105 transition transform duration-200">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
