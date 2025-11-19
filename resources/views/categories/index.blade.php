@extends('layouts.app')
@section('page-title', 'Daftar Kategori')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-700 p-8 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold">Manajemen Kategori</h1>
                    <p class="opacity-90 mt-2">Total: {{ $categories->total() }} kategori</p>
                </div>
                <a href="{{ route('categories.create') }}"
                   class="bg-white text-indigo-700 px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-100 transition shadow-2xl transform hover:scale-105">
                    + Tambah Kategori
                </a>
            </div>
        </div>

        <div class="p-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid gap-6">
                @forelse($categories as $cat)
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-8 rounded-2xl border-2 border-gray-300 hover:border-indigo-400 transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-indigo-800">{{ $cat->name }}</h3>
                            @if($cat->description)
                                <p class="text-gray-600 mt-2">{{ Str::limit($cat->description, 100) }}</p>
                            @endif
                            <p class="text-sm text-gray-500 mt-3">
                                Digunakan oleh <strong>{{ $cat->products()->count() }}</strong> produk
                            </p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('categories.edit', $cat) }}"
                               class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                                Edit
                            </a>
                            <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Yakin hapus kategori {{ $cat->name }}?')"
                                        class="px-6 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-20 text-gray-500">
                    <p class="text-2xl">Belum ada kategori</p>
                    <a href="{{ route('categories.create') }}" class="text-indigo-600 underline mt-4 inline-block text-lg">
                        Tambah kategori pertama sekarang →
                    </a>
                </div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
