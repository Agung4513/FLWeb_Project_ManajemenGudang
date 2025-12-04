@extends('layouts.app')
@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <a href="{{ route('categories.index') }}" class="inline-flex items-center text-gray-500 hover:text-indigo-600 font-medium mb-6 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar
    </a>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-indigo-50 px-8 py-6 border-b border-indigo-100 flex items-center gap-4">
            <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center font-bold">
                <i class="fa-solid fa-pen"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-indigo-900">Edit: {{ $category->name }}</h2>
                <p class="text-indigo-500 text-sm">Perbarui informasi kategori ini.</p>
            </div>
        </div>

        <form action="{{ route('categories.update', $category) }}" method="POST" class="p-8 md:p-10">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-4 text-gray-400">
                            <i class="fa-solid fa-tag"></i>
                        </span>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                               class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-xl font-medium text-slate-700 placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-100 focus:outline-none transition"
                               placeholder="Contoh: Elektronik">
                    </div>
                    @error('name') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi</label>
                    <textarea name="description" rows="4"
                              class="w-full p-4 border-2 border-gray-200 rounded-xl font-medium text-slate-700 placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-100 focus:outline-none transition resize-none"
                              placeholder="Deskripsi kategori...">{{ old('description', $category->description) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-10 pt-6 border-t border-gray-100">
                <a href="{{ route('categories.index') }}" class="px-6 py-3 text-slate-500 font-bold hover:text-slate-700 transition">
                    Batal
                </a>
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3.5 rounded-xl font-bold hover:shadow-lg hover:scale-105 transition transform duration-200">
                    <i class="fa-solid fa-check mr-2"></i> Update Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
