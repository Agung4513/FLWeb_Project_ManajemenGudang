<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gudang Jaya')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

<div class="flex h-screen">
    <aside class="w-64 bg-indigo-800 text-white flex flex-col">
        <div class="p-6 text-center border-b border-indigo-700">
            <h1 class="text-2xl font-bold">Gudang Jaya</h1>
            <p class="text-xs text-indigo-200">Manajemen Gudang</p>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="{{ route(Auth::user()->role . '.dashboard') }}"
               class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs(Auth::user()->role . '.*') ? 'bg-indigo-900 text-white font-bold' : 'hover:bg-indigo-700 text-indigo-100' }}">
                Beranda
            </a>

            @if(in_array(Auth::user()->role, ['admin', 'manager']))
                <a href="{{ route('products.index') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-indigo-700 text-indigo-100 {{ request()->routeIs('products.*') ? 'bg-indigo-900 font-bold' : '' }}">
                    Produk
                </a>
                <a href="{{ route('categories.index') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-indigo-700 text-indigo-100 {{ request()->routeIs('categories.*') ? 'bg-indigo-900 font-bold' : '' }}">
                    Kategori
                </a>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'manager']))
                <a href="{{ route('restock-orders.index') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-indigo-700 text-indigo-100 {{ request()->routeIs('restock-orders.*') ? 'bg-indigo-900 font-bold' : '' }}">
                    Daftar Restock Order

            @endif

            @if(in_array(Auth::user()->role, ['admin', 'manager', 'staff']))
                <a href="{{ route('transactions.index') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-indigo-700 text-indigo-100 {{ request()->routeIs('transactions.*') ? 'bg-indigo-900 font-bold' : '' }}">
                    Riwayat Transaksi
                </a>
                <a href="{{ route('transactions.create') }}"
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-indigo-700 text-indigo-100 {{ request()->routeIs('transactions.create') ? 'bg-indigo-900 font-bold' : '' }}">
                    Transaksi Baru
                </a>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'manager']))
                <a href="#" class="flex items-center px-4 py-3 rounded-lg opacity-60 cursor-not-allowed text-indigo-200">
                    Laporan (segera)
                </a>
            @endif
        </nav>

        <div class="p-4 border-t border-indigo-700 text-center text-sm">
            <p class="font-bold">{{ Auth::user()->name }}</p>
            <p class="text-indigo-300">{{ ucfirst(Auth::user()->role) }}</p>
        </div>
    </aside>

    <div class="flex-1 flex flex-col">
        <header class="bg-white shadow-sm border-b px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-red-600 hover:text-red-800 font-medium">Logout</button>
            </form>
        </header>
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
