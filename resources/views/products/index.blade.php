@extends('layouts.app')

@section('sidebar')
    @include('admin.dashboard', ['sidebarOnly' => true])
@endsection

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Daftar Produk</h1>
        <p class="text-gray-600">Halaman ini akan menampilkan daftar produk. Fitur ini belum lengkap.</p>
        <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Tambah Produk</a>
    </div>
@endsection
