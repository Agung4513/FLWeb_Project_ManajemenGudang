@extends('layouts.app')

@section('content')
    <div class="bg-white shadow-sm rounded-lg p-6 text-center max-w-md mx-auto mt-10">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Selamat Datang di Manajemen Gudang</h1>
        <p class="text-gray-600 mb-6">Kelola inventori, stok, dan transaksi dengan mudah. Silakan login atau daftar untuk memulai.</p>
        <div class="space-x-4">
            <a href="{{ route('login') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Login</a>
            <a href="{{ route('register') }}" class="inline-block bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">Daftar</a>
        </div>
        <div class="mt-6 text-sm text-gray-500">
            <p>Catatan: Pendaftaran hanya untuk Supplier dan memerlukan approval Admin.</p>
        </div>
    </div>
@endsection
