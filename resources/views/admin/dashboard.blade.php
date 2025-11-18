@extends('layouts.app')

@section('sidebar')
    <div class="w-64 bg-gray-800 text-white p-4 h-screen">
        <h2 class="text-2xl font-bold mb-6">Admin Panel</h2>
        <nav>
            <ul>
                <li class="mb-2"><a href="{{ route('admin.dashboard') }}" class="hover:text-gray-300">Beranda</a></li>
                <li class="mb-2"><a href="{{ route('admin.products.index') }}" class="hover:text-gray-300">Produk</a></li>
                <li class="mb-2"><a href="{{ route('admin.categories.index') }}" class="hover:text-gray-300">Kategori</a></li>
                {{-- <li class="mb-2"><a href="{{ route('admin.users.index') }}" class="hover:text-gray-300">Pengguna</a></li> --}}
                <li class="mb-2"><a href="{{ route('admin.reports') }}" class="hover:text-gray-300">Laporan</a></li>
            </ul>
        </nav>
    </div>
@endsection

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Dashboard Admin</h1>
        <p class="text-gray-600">Selamat datang, Admin! Kelola produk, kategori, dan pengguna di sini.</p>
    </div>
@endsection
