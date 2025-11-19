@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Admin</h2>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex gap-8">

        <div class="flex-1">
            <div class="bg-white shadow rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Selamat datang, Admin!</h1>
                <p class="text-lg text-gray-600">Kelola produk, kategori, transaksi, dan laporan dengan mudah.</p>
            </div>
        </div>

    </div>
</div>
@endsection
