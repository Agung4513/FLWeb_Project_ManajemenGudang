@extends('layouts.app')

@section('title', 'Dashboard Manager')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard Manager
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">Selamat datang, Manager!</h1>
                        <p class="text-lg text-gray-600">Kelola produk, transaksi, restock, dan laporan dengan mudah.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
