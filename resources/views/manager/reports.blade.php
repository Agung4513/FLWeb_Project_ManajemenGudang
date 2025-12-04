@extends('layouts.app')

@section('sidebar')
    @include('manager.dashboard', ['sidebarOnly' => true])
@endsection

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Laporan Manager</h1>
        <p class="text-gray-600">Halaman ini akan menampilkan laporan manajerial. Fitur ini belum lengkap.</p>
    </div>
@endsection
