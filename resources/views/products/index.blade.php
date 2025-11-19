@extends('layouts.app')
@section('page-title', 'Daftar Produk')

@section('content')
<div class="max-w-7xl mx-auto py-10">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-700 p-8 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-extrabold">Manajemen Produk</h1>
                    <p class="text-xl opacity-90 mt-2">Total: {{ $products->total() }} produk</p>
                </div>
                <a href="{{ route('products.create') }}"
                   class="bg-white text-indigo-700 px-8 py-4 rounded-full font-bold text-lg shadow-2xl hover:bg-gray-100 transition transform hover:scale-105">
                    + Tambah Produk
                </a>
            </div>
        </div>

        <div class="p-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">
                            <th class="px-6 py-4">SKU</th>
                            <th class="px-6 py-4">Nama Produk</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Stok</th>
                            <th class="px-6 py-4">Harga Beli</th>
                            <th class="px-6 py-4">Harga Jual</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($products as $p)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-mono font-bold text-indigo-700">{{ $p->sku }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('products.show', $p) }}" class="text-lg font-semibold text-gray-900 hover:text-indigo-600">
                                    {{ $p->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $p->category->name }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="{{ $p->is_low_stock ? 'text-red-600 font-bold' : 'text-gray-800' }}">
                                        {{ $p->current_stock }} {{ $p->unit }}
                                    </span>
                                    @if($p->is_low_stock)
                                        <span class="ml-3 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 animate-pulse">
                                            STOK RENDAH!
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $p->formatted_buy_price }}</td>
                            <td class="px-6 py-4 font-bold text-green-600">{{ $p->formatted_sell_price }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('products.edit', $p) }}" class="text-indigo-600 hover:underline mr-4">Edit</a>
                                <form method="POST" action="{{ route('products.destroy', $p) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Yakin hapus {{ addslashes($p->name) }}?')"
                                            class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-20 text-gray-500">
                                <p class="text-2xl">Belum ada produk</p>
                                <a href="{{ route('products.create') }}" class="text-indigo-600 underline text-lg mt-4 inline-block">
                                    Tambah produk pertama sekarang
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
