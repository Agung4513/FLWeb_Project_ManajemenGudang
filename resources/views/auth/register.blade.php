<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Supplier - Gudang Jaya</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-slate-900 text-white selection:bg-indigo-500 selection:text-white">

    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl mix-blend-screen animate-pulse"></div>
        <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl mix-blend-screen animate-pulse" style="animation-delay: 2s"></div>
    </div>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10 px-4 py-10">

        <div class="mb-6 text-center">
            <a href="/" class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-xl hover:rotate-6 transition duration-300">
                <i class="fa-solid fa-handshake text-2xl text-white"></i>
            </a>
            <h2 class="mt-4 text-2xl font-extrabold tracking-tight text-white">Mitra Supplier Baru</h2>
        </div>

        <div class="w-full sm:max-w-lg bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2rem] shadow-2xl p-8 overflow-hidden">

            <div class="mb-6 bg-indigo-500/10 border border-indigo-500/30 rounded-xl p-4 flex items-start">
                <i class="fa-solid fa-circle-info text-indigo-400 mt-0.5 mr-3"></i>
                <div class="text-sm text-indigo-200">
                    <span class="font-bold text-white">Catatan Penting:</span><br>
                    Akun ini akan didaftarkan sebagai <strong>Supplier</strong>. Anda memerlukan persetujuan Admin sebelum dapat login ke sistem.
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-5">
                    <label for="name" class="block font-medium text-sm text-slate-300 mb-2">Nama Perusahaan / Supplier</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-500"><i class="fa-solid fa-building"></i></span>
                        <input id="name" class="block w-full pl-11 pr-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50 focus:outline-none transition placeholder-slate-600"
                               type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="PT. Supplier Maju" />
                    </div>
                    @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mb-5">
                    <label for="email" class="block font-medium text-sm text-slate-300 mb-2">Email Bisnis</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-500"><i class="fa-solid fa-envelope"></i></span>
                        <input id="email" class="block w-full pl-11 pr-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50 focus:outline-none transition placeholder-slate-600"
                               type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="kontak@perusahaan.com" />
                    </div>
                    @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="block font-medium text-sm text-slate-300 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-500"><i class="fa-solid fa-lock"></i></span>
                        <input id="password" class="block w-full pl-11 pr-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50 focus:outline-none transition placeholder-slate-600"
                               type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                    </div>
                    @error('password') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block font-medium text-sm text-slate-300 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-500"><i class="fa-solid fa-check-double"></i></span>
                        <input id="password_confirmation" class="block w-full pl-11 pr-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50 focus:outline-none transition placeholder-slate-600"
                               type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" />
                    </div>
                    @error('password_confirmation') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-purple-500/30 transition transform hover:-translate-y-0.5">
                        Daftar Sekarang
                    </button>
                </div>

                <div class="mt-6 text-center border-t border-white/10 pt-4">
                    <a href="{{ route('login') }}" class="text-slate-400 hover:text-white text-sm transition">
                        Sudah punya akun? <span class="text-indigo-400 font-bold underline">Login di sini</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
