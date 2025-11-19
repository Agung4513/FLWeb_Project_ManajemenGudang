<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gudang Jaya - Sistem Manajemen Gudang</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600">

    <div class="min-h-screen flex items-center justify-center px-6">
        <div class="max-w-lg w-full text-center">
            <h1 class="text-7xl font-extrabold text-white mb-4 drop-shadow-2xl">
                Gudang Jaya
            </h1>
            <p class="text-2xl text-white/90 mb-12 font-medium">
                Sistem Manajemen Gudang Modern
            </p>

            <div class="bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl p-10 border border-white/30">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">
                    Selamat Datang!
                </h2>
                <p class="text-gray-700 mb-10 leading-relaxed text-lg">
                    Kelola inventori, stok, transaksi, dan restock dengan mudah, aman, dan terintegrasi.
                </p>

                <div class="grid grid-cols-1 gap-5">
                    <a href="{{ route('login') }}"
                       class="block bg-indigo-600 text-white py-4 rounded-2xl font-bold text-lg hover:bg-indigo-700 transform hover:scale-105 transition shadow-xl">
                        Login ke Sistem
                    </a>

                    <a href="{{ route('register') }}"
                       class="block bg-gradient-to-r from-green-500 to-emerald-600 text-white py-4 rounded-2xl font-bold text-lg hover:from-green-600 hover:to-emerald-700 transform hover:scale-105 transition shadow-xl">
                        Daftar sebagai Supplier
                    </a>
                </div>

                <div class="mt-8 p-5 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        <strong class="text-gray-800">Catatan:</strong><br>
                        Pendaftaran hanya untuk <strong>Supplier</strong>. Akun akan aktif setelah mendapat persetujuan dari Admin.
                    </p>
                </div>
            </div>

            <footer class="mt-12 text-white/80 text-sm">
                <p>© 2025 Gudang Jaya — Tugas Final Praktikum Pemrograman Web 2025</p>
                <p class="mt-2">Deadline: 30 November 2025 | Presentasi: 1–5 Desember 2025</p>
            </footer>
        </div>
    </div>

</body>
</html>
