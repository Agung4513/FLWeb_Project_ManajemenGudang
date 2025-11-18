@extends('layouts.app')

@section('sidebar')
    @include('supplier.dashboard', ['sidebarOnly' => true])
@endsection

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Pesanan Supplier</h1>
        <p class="text-gray-600">Halaman ini akan menampilkan daftar pesanan. Fitur ini belum lengkap.</p>
    </div>
@endsection
