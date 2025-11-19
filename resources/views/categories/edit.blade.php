@extends('layouts.app')
@section('page-title', 'Edit Kategori: ' . $category->name)

@section('content')
<div class="max-w-2xl mx-auto py-20">
    <div class="bg-white rounded-3xl shadow-2xl p-12">
        <h1 class="text-4xl font-bold text-center text-indigo-700 mb-10">
            Edit Kategori
        </h1>
        <p class="text-center text-gray-600 mb-10">
            Kategori: <strong>{{ $category->name }}</strong>
        </p>

        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <div>
                    <label class="block text-xl font-bold text-gray-700 mb-4">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $category->name) }}"
                           required
                           class="w-full border-2 border-indigo-200 rounded-2xl px-6 py-5 text-lg focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition"
                           placeholder="Contoh: Elektronik">
                    @error('name')
                        <span class="text-red-600 text-sm block mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xl font-bold text-gray-700 mb-4">
                        Deskripsi (Opsional)
                    </label>
                    <textarea name="description"
                              rows="5"
                              class="w-full border-2 border-indigo-200 rounded-2xl px-6 py-5 text-lg focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition"
                              placeholder="Jelaskan kategori ini...">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <span class="text-red-600 text-sm block mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex gap-6 justify-center pt-10">
                    <a href="{{ route('categories.index') }}"
                       class="px-10 py-5 bg-gray-500 text-white rounded-2xl font-bold text-xl hover:bg-gray-600 transition transform hover:scale-105">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-12 py-5 bg-gradient-to-r from-green-600 to-emerald-700 text-white rounded-2xl font-bold text-xl hover:from-green-700 hover:to-emerald-800 transition shadow-2xl transform hover:scale-110">
                        Update Kategori
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
