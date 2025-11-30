<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gudang Jaya - WMS</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-slate-900 text-white selection:bg-indigo-500 selection:text-white">

    <!-- Background Effects -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-600/30 rounded-full blur-3xl mix-blend-screen animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-600/30 rounded-full blur-3xl mix-blend-screen animate-pulse" style="animation-delay: 2s"></div>
    </div>

    <div class="relative z-10 min-h-screen flex flex-col justify-center items-center px-6 py-12">

        <!-- Logo / Brand Area -->
        <div class="text-center mb-10 animate-fade-in-down">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl shadow-2xl mb-6 transform hover:rotate-6 transition duration-500 cursor-default">
                <i class="fa-solid fa-boxes-stacked text-4xl text-white"></i>
            </div>
            <h1 class="text-5xl md:text-7xl font-black tracking-tight mb-2 bg-clip-text text-transparent bg-gradient-to-r from-white via-indigo-100 to-indigo-300">
                GUDANG JAYA
            </h1>
            <p class="text-lg md:text-xl text-indigo-200 font-medium tracking-wide">
                Warehouse Management System Enterprise
            </p>
        </div>

        <!-- Main Card -->
        <div class="w-full max-w-4xl bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] shadow-2xl overflow-hidden p-1">
            <div class="grid grid-cols-1 md:grid-cols-2">

                <!-- Left: Login/Action -->
                <div class="p-10 md:p-12 flex flex-col justify-center">
                    <h2 class="text-3xl font-bold mb-4">Akses Sistem</h2>
                    <p class="text-slate-400 mb-8 leading-relaxed">
                        Platform terintegrasi untuk manajemen stok, tracking transaksi, dan pengadaan barang secara real-time.
                    </p>

                    <div class="space-y-4">
                        <a href="{{ route('login') }}"
                           class="group block w-full bg-indigo-600 hover:bg-indigo-500 text-white text-center font-bold py-4 rounded-xl transition-all shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:-translate-y-1">
                            <i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk ke Dashboard
                        </a>

                        <div class="relative flex py-2 items-center">
                            <div class="flex-grow border-t border-white/10"></div>
                            <span class="flex-shrink-0 mx-4 text-slate-500 text-xs uppercase font-bold">Mitra Baru</span>
                            <div class="flex-grow border-t border-white/10"></div>
                        </div>

                        <a href="{{ route('register') }}"
                           class="group block w-full bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white text-center font-bold py-4 rounded-xl transition-all hover:-translate-y-1">
                            Daftar sebagai Supplier
                        </a>
                    </div>
                </div>

                <!-- Right: Features / Visual -->
                <div class="bg-gradient-to-br from-indigo-900/50 to-purple-900/50 p-10 md:p-12 flex flex-col justify-center border-t md:border-t-0 md:border-l border-white/5">
                    <h3 class="text-lg font-bold text-white mb-6 uppercase tracking-wider text-xs opacity-70">Fitur Unggulan</h3>

                    <ul class="space-y-6">
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-bold text-white">Monitoring Real-time</h4>
                                <p class="text-sm text-slate-400 mt-1">Pantau pergerakan stok masuk dan keluar secara langsung.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-bold text-white">Keamanan Berlapis</h4>
                                <p class="text-sm text-slate-400 mt-1">Validasi transaksi Maker-Checker dan otorisasi role ketat.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-orange-500/20 flex items-center justify-center text-orange-400">
                                <i class="fa-solid fa-truck-fast"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-bold text-white">Integrasi Supplier</h4>
                                <p class="text-sm text-slate-400 mt-1">Portal khusus untuk manajemen PO dan pengiriman.</p>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-12 text-center">
            <p class="text-slate-500 text-sm">
                &copy; {{ date('Y') }} Gudang Jaya. Sistem Manajemen Terpadu.
            </p>
        </footer>

    </div>

    <style>
        .animate-fade-in-down { animation: fadeInDown 0.8s ease-out; }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>
