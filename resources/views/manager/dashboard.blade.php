@extends('layouts.app')

@section('sidebar')
    <div class="w-64 bg-gray-800 text-white p-4 h-screen">
        <h2 class="text-2xl font-bold mb-6">Manager Panel</h2>
        <nav>
            <ul>
                <li class="mb-2"><a href="{{ route('manager.dashboard') }}" class="hover:text-gray-300">Beranda</a></li>
                <li class="mb-2"><a href="{{ route('manager.transactions.index') }}" class="hover:text-gray-300">Transaksi</a></li>
                <li class="mb-2"><a href="{{ route('manager.restock-orders.index') }}" class="hover:text-gray-300">Restock</a></li>
                <li class="mb-2"><a href="{{ route('manager.reports') }}" class="hover:text-gray-300">Laporan</a></li>
            </ul>
        </nav>
    </div>
@endsection

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Dashboard Manager</h1>
        <p class="text-gray-600">Selamat datang, Manager! Kelola transaksi, restock, dan laporan di sini.</p>
    </div>
@endsection
