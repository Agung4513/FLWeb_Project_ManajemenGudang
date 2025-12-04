@extends('layouts.app')

@section('sidebar')
    @include('staff.dashboard', ['sidebarOnly' => true])
@endsection

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Edit Transaksi</h1>
        <p class="text-gray-600">Halaman ini akan menampilkan form untuk mengedit transaksi. Fitur ini belum lengkap.</p>
        <a href="{{ route('staff.transactions.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Kembali</a>
    </div>
@endsection
