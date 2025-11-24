@extends('layouts.app')
@section('title', 'Dashboard Supplier')
@section('content')
<div class="max-w-7xl mx-auto py-12">
    <div class="text-center mb-12">
        <h1 class="text-6xl font-bold text-purple-800 mb-4">
            Selamat Datang, {{ auth()->user()->name }}!
        </h1>
        <p class="text-3xl text-gray-600">Partner Gudang Jaya</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-16">
        <div class="bg-gradient-to-br from-blue-500 to-cyan-600 text-white p-12 rounded-3xl shadow-2xl text-center transform hover:scale-105 transition">
            <h3 class="text-3xl font-bold mb-4">Total PO</h3>
            <p class="text-7xl font-bold">{{ $totalOrders }}</p>
        </div>
        <div class="bg-gradient-to-br from-red-500 to-pink-600 text-white p-12 rounded-3xl shadow-2xl text-center transform hover:scale-105 transition">
            <h3 class="text-3xl font-bold mb-4">Menunggu Konfirmasi</h3>
            <p class="text-7xl font-bold">{{ $pendingOrders }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white p-12 rounded-3xl shadow-2xl text-center transform hover:scale-105 transition">
            <h3 class="text-3xl font-bold mb-4">Sudah Dikonfirmasi</h3>
            <p class="text-7xl font-bold">{{ $confirmedOrders }}</p>
        </div>
    </div>

    <div class="text-center mb-16">
        <a href="{{ route('supplier.restock-orders.index') }}"
           class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold text-4xl px-20 py-12 rounded-3xl shadow-3xl transform hover:scale-110 transition">
            LIHAT SEMUA PURCHASE ORDER
        </a>
    </div>

    @if($pendingOrders > 0)
    <div class="bg-red-50 border-8 border-red-500 rounded-3xl p-12">
        <h2 class="text-5xl font-bold text-red-800 text-center mb-10">
            ADA {{ $pendingOrders }} PO MENUNGGU KONFIRMASI ANDA!
        </h2>
        <div class="space-y-6">
            @foreach($latestPending as $order)
            <div class="bg-white rounded-2xl shadow-xl p-8 flex justify-between items-center hover:shadow-2xl transition">
                <div>
                    <h3 class="text-3xl font-bold text-purple-700">{{ $order->po_number }}</h3>
                    <p class="text-xl text-gray-600 mt-2">
                        Estimasi Tiba: <span class="font-bold text-orange-600">
                            {{ $order->expected_delivery_date->format('d F Y') }}
                        </span>
                    </p>
                </div>
                <a href="{{ route('supplier.restock-orders.show', $order) }}"
                   class="bg-red-600 hover:bg-red-700 text-white font-bold text-2xl px-12 py-8 rounded-2xl shadow-xl transform hover:scale-110 transition">
                    KONFIRMASI SEKARANG
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-green-50 border-8 border-green-500 rounded-3xl p-20 text-center">
        <h2 class="text-5xl font-bold text-green-800">
            Semua PO Sudah Anda Tanggapi!
        </h2>
        <p class="text-3xl text-gray-700 mt-8">Terima kasih atas kerjasamanya</p>
    </div>
    @endif
</div>
@endsection
