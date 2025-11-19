@extends('layouts.app')
@section('page-title', 'Tambah Kategori Baru')

@section('content')
<div class="max-w-2xl mx-auto py-20">
    <div class="bg-white rounded-3xl shadow-2xl p-12">
        <h1 class="text-4xl font-bold text-center text-indigo-700 mb-10">Tambah Kategori Baru</h1>

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="space-y-8">
                <div>
                    <label class="block text-xl font-bold text-gray-700 mb-4">Nama Kategori</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border-2 border-indigo-200 rounded-2xl px-6 py-5 text-lg focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100"
                           placeholder="Contoh: Elektronik, Makanan Ringan">
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xl font-bold text-gray-700 mb-4">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="5"
                              class="w-full border-2 border-indigo-200 rounded-2xl px-6 py-5 text-lg focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100"
                              placeholder="Jelaskan kategori ini...">{{ old('description') }}</textarea>
                </div>

                <div class="flex gap-6 justify-center pt-8">
                    <a href="{{ route('categories.index') }}" class="px-10 py-5 bg-gray-500 text-white rounded-2xl font-bold text-xl hover:bg-gray-600 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-12 py-5 bg-gradient-to-r from-indigo-600 to-purple-700 text-white rounded-2xl font-bold text-xl hover:from-indigo-700 hover:to-purple-800 transition shadow-2xl transform hover:scale-105">
                        Simpan Kategori
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
