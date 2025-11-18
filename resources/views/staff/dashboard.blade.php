@extends('layouts.app')

@section('sidebar')
    <div class="w-64 bg-gray-800 text-white p-4 h-screen">
        <h2 class="text-2xl font-bold mb-6">Staff Panel</h2>
        <nav>
            <ul>
                <li class="mb-2"><a href="{{ route('staff.dashboard') }}" class="hover:text-gray-300">Beranda</a></li>
                <li class="mb-2"><a href="{{ route('staff.transactions.index') }}" class="hover:text-gray-300">Transaksi</a></li>
                <li class="mb-2"><a href="{{ route('staff.stock') }}" class="hover:text-gray-300">Stok</a></li>
            </ul>
        </nav>
    </div>
@endsection

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Dashboard Staff</h1>
        <p class="text-gray-600">Selamat datang, Staff! Kelola transaksi dan stok di sini.</p>
    </div>
@endsection
