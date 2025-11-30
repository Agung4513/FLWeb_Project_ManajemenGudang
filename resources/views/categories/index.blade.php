@extends('layouts.app')
@section('title', 'Daftar Kategori')
@section('page-title', 'Master Data Kategori')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-3xl shadow-xl overflow-hidden mb-8 text-white relative">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <i class="fa-solid fa-tags text-9xl"></i>
        </div>
        <div class="p-8 md:p-10 relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Manajemen Kategori</h1>
                <p class="text-indigo-100 mt-2 text-lg">Kelola pengelompokan produk gudang Anda.</p>
                <div class="mt-4 inline-flex items-center bg-white/20 px-4 py-2 rounded-lg backdrop-blur-sm">
                    <i class="fa-solid fa-layer-group mr-2"></i>
                    <span class="font-bold">{{ $categories->total() }}</span> <span class="ml-1 text-sm opacity-80">Total Kategori</span>
                </div>
            </div>
            <a href="{{ route('categories.create') }}"
               class="group bg-white text-indigo-700 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-indigo-50 transition shadow-lg transform hover:-translate-y-1 flex items-center">
                <i class="fa-solid fa-plus mr-2 group-hover:rotate-90 transition-transform"></i>
                Tambah Kategori
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-xl shadow-sm mb-6 flex items-center">
            <i class="fa-solid fa-check-circle text-xl mr-3"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-xl shadow-sm mb-6 flex items-center">
            <i class="fa-solid fa-triangle-exclamation text-xl mr-3"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $cat)
        <div class="group bg-white rounded-3xl p-6 shadow-md hover:shadow-2xl border border-gray-100 hover:border-indigo-100 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-bl-full -mr-16 -mt-16 transition-all group-hover:bg-indigo-100"></div>

            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <div class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">
                        {{ $cat->products_count }} Produk
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-slate-800 mb-2 group-hover:text-indigo-700 transition-colors">
                    {{ $cat->name }}
                </h3>

                <p class="text-gray-500 text-sm mb-6 line-clamp-2 min-h-[40px]">
                    {{ $cat->description ?: 'Tidak ada deskripsi untuk kategori ini.' }}
                </p>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('categories.edit', $cat) }}"
                       class="flex-1 bg-gray-50 hover:bg-indigo-50 text-gray-600 hover:text-indigo-600 py-2.5 rounded-xl font-bold text-sm text-center transition border border-gray-200 hover:border-indigo-200">
                       <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                    </a>

                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin hapus kategori {{ $cat->name }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-white hover:bg-red-50 text-gray-400 hover:text-red-500 py-2.5 rounded-xl font-bold text-sm transition border border-gray-200 hover:border-red-200">
                            <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center">
            <div class="inline-block p-6 rounded-full bg-gray-100 mb-4">
                <i class="fa-solid fa-folder-open text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-600">Belum ada kategori</h3>
            <p class="text-gray-400 mt-2">Silakan tambahkan kategori baru untuk memulai.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $categories->links() }}
    </div>
</div>
@endsection
