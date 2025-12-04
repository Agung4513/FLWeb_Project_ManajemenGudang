@extends('layouts.app')
@section('title', 'Stok Gudang - Staff')
@section('page-title', 'Cek Stok Gudang')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="text-center mb-12">
        <h1 class="text-9xl font-extrabold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">
            STOK GUDANG
        </h1>
        <p class="text-4xl text-gray-700 mt-6">Real-time stock monitoring</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach(\App\Models\Product::all() as $p)
        <div class="bg-white rounded-3xl shadow-3xl p-12 text-center border-8
            {{ $p->current_stock <= 10 ? 'border-red-500' : 'border-blue-400' }}
            transform hover:scale-105 transition">
            <h3 class="text-4xl font-extrabold text-gray-800 mb-6">{{ $p->name }}</h3>
            <p class="text-9xl font-black
                {{ $p->current_stock <= 10 ? 'text-red-600' : 'text-blue-600' }}">
                {{ $p->current_stock }}
            </p>
            <p class="text-3xl mt-4 font-bold
                {{ $p->current_stock <= 10 ? 'text-red-600' : 'text-gray-600' }}">
                {{ $p->current_stock <= 10 ? 'STOK RENDAH!' : 'Aman' }}
            </p>
        </div>
        @endforeach
    </div>
</div>
@endsection
