<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gudang Jaya')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gray-50 text-slate-800">

<div class="flex h-screen overflow-hidden">

    <aside class="w-72 bg-gradient-to-b from-slate-900 to-indigo-900 text-white flex flex-col shadow-2xl relative z-20">
        <div class="p-8 text-center border-b border-white/10">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-400 to-orange-500 mb-3 shadow-lg">
                <i class="fa-solid fa-boxes-stacked text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight">
                GUDANG <span class="text-yellow-400">JAYA</span>
            </h1>
            <p class="text-indigo-200 text-xs mt-1 tracking-wider uppercase opacity-70">Sistem Manajemen v1.0</p>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto scrollbar-thin scrollbar-thumb-indigo-500 scrollbar-track-transparent">
            @auth
                @php $dashboardRoute = auth()->user()->getDashboardRoute(); @endphp
                <a href="{{ route($dashboardRoute) }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group mb-4 {{ request()->routeIs($dashboardRoute) ? 'bg-white/10 text-white shadow-lg backdrop-blur-sm border border-white/10' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-house w-6 text-center mr-3 text-lg {{ request()->routeIs($dashboardRoute) ? 'text-yellow-400' : '' }}"></i>
                    <span class="font-semibold">Beranda</span>
                </a>

                @if(auth()->user()->role === 'admin')
                    <div class="px-4 mt-6 mb-2 text-xs font-bold text-indigo-300 uppercase tracking-widest">Administrasi</div>

                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                        <i class="fa-solid fa-users-gear w-6 text-center mr-3"></i><span>Kelola Pengguna</span>
                        @if(\App\Models\User::where('is_active', false)->count() > 0)
                            <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse shadow-red-500/50">{{ \App\Models\User::where('is_active', false)->count() }}</span>
                        @endif
                    </a>

                    <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('categories.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                        <i class="fa-solid fa-tags w-6 text-center mr-3"></i><span>Master Kategori</span>
                    </a>
                @endif

                @if(in_array(auth()->user()->role, ['admin', 'manager']))
                    @if(auth()->user()->role !== 'admin')
                        <div class="px-4 mt-6 mb-2 text-xs font-bold text-indigo-300 uppercase tracking-widest">Manajemen</div>
                        <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('categories.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-tags w-6 text-center mr-3"></i><span>Master Kategori</span>
                        </a>
                    @endif

                    <a href="{{ route('products.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('*.products.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                        <i class="fa-solid fa-box-open w-6 text-center mr-3"></i><span>Produk & Stok</span>
                    </a>

                    <a href="{{ route('restock-orders.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('restock-orders.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                        <i class="fa-solid fa-truck-ramp-box w-6 text-center mr-3"></i><span>Restock Order</span>
                    </a>
                @endif

                @if(in_array(auth()->user()->role, ['manager', 'staff']))
                    <div class="px-4 mt-6 mb-2 text-xs font-bold text-indigo-300 uppercase tracking-widest">Operasional</div>

                    @if(auth()->user()->role === 'staff')
                        <a href="{{ route('staff.transactions.create') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group mb-1 {{ request()->routeIs('*.transactions.create') ? 'bg-emerald-600 text-white shadow-lg border border-emerald-500' : 'text-emerald-300 hover:bg-emerald-500/20 hover:text-emerald-200' }}">
                            <i class="fa-solid fa-cart-plus w-6 text-center mr-3"></i><span class="font-bold">+ Transaksi Baru</span>
                        </a>
                        <a href="{{ route('staff.stock') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('staff.stock') ? 'bg-indigo-600 text-white shadow-md' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                            <i class="fa-solid fa-magnifying-glass-chart w-6 text-center mr-3"></i><span>Cek Stok Gudang</span>
                        </a>
                    @endif

                    <a href="{{ route(auth()->user()->role . '.transactions.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('*.transactions.*') && !request()->routeIs('*.transactions.create') ? 'bg-indigo-600 text-white shadow-md' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                        <i class="fa-solid fa-clock-rotate-left w-6 text-center mr-3"></i><span>Riwayat Transaksi</span>
                    </a>
                @endif

                @if(auth()->user()->role === 'supplier')
                    <div class="px-4 mt-6 mb-2 text-xs font-bold text-indigo-300 uppercase tracking-widest">Menu Supplier</div>
                    <a href="{{ route('supplier.restock-orders.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('supplier.restock-orders.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                        <i class="fa-solid fa-dolly w-6 text-center mr-3"></i><span>PO Masuk</span>
                    </a>
                @endif
            @endauth
        </nav>

        <div class="p-4 border-t border-white/10 bg-black/20">
            @auth
                <div class="flex items-center mb-3 px-2">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-400 to-purple-400 flex items-center justify-center text-white font-bold text-lg shadow-md mr-3">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="font-bold text-sm text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-indigo-300 text-xs capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <a href="{{ route('logout.get') }}" class="w-full flex items-center justify-center bg-red-500/10 hover:bg-red-500 text-red-300 hover:text-white py-2 rounded-lg text-sm font-medium transition duration-200 border border-red-500/20">
                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                </a>
            @endauth
        </div>
    </aside>

    <div class="flex-1 flex flex-col relative overflow-hidden">
        <header class="bg-white/80 backdrop-blur-md border-b border-gray-200 h-20 flex justify-between items-center px-8 z-10">
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">@yield('page-title', 'Dashboard')</h2>
            <div class="flex items-center space-x-4">
                <div class="hidden md:flex items-center text-sm font-medium text-slate-500 bg-slate-100 px-4 py-2 rounded-lg">
                    <i class="fa-regular fa-calendar mr-2"></i>{{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </header>
        <main class="flex-1 overflow-y-auto p-6 md:p-8 bg-gray-50 scrollbar-thin scrollbar-thumb-gray-300">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
