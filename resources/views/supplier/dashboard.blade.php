@extends('layouts.app')

@section('sidebar')
    <div class="w-64 bg-gray-800 text-white p-4 h-screen">
        <h2 class="text-2xl font-bold mb-6">Supplier Panel</h2>
        <nav>
            <ul>
                <li class="mb-2"><a href="{{ route('supplier.dashboard') }}" class="hover:text-gray-300">Beranda</a></li>
                <li class="mb-2"><a href="{{ route('supplier.restock-orders.index') }}" class="hover:text-gray-300">Restock</a></li>
                <li class="mb-2"><a href="{{ route('supplier.orders') }}" class="hover:text-gray-300">Pesanan</a></li>
            </ul>
        </nav>
    </div>
@endsection

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Dashboard Supplier</h1>
        <p class="text-gray-600">Selamat datang, Supplier! Kelola restock dan pesanan di sini.</p>
    </div>
@endsection
