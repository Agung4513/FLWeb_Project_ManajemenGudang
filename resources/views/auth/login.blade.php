<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Gudang Jaya</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-slate-900 text-white selection:bg-indigo-500 selection:text-white">

    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl mix-blend-screen animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl mix-blend-screen animate-pulse" style="animation-delay: 2s"></div>
    </div>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10 px-4">

        <div class="mb-8 text-center">
            <a href="/" class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-xl hover:rotate-6 transition duration-300">
                <i class="fa-solid fa-boxes-stacked text-3xl text-white"></i>
            </a>
            <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-white">Selamat Datang Kembali</h2>
            <p class="text-slate-400 mt-1">Masuk untuk mengelola gudang Anda.</p>
        </div>

        <div class="w-full sm:max-w-md bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl p-8 overflow-hidden">

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-emerald-400 text-center bg-emerald-400/10 p-3 rounded-xl border border-emerald-400/20">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-6">
                    <label for="email" class="block font-medium text-sm text-slate-300 mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-500"><i class="fa-solid fa-envelope"></i></span>
                        <input id="email" class="block w-full pl-11 pr-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50 focus:outline-none transition placeholder-slate-600"
                               type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com" />
                    </div>
                    @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block font-medium text-sm text-slate-300 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-500"><i class="fa-solid fa-lock"></i></span>
                        <input id="password" class="block w-full pl-11 pr-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50 focus:outline-none transition placeholder-slate-600"
                               type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                    </div>
                    @error('password') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-indigo-400 hover:text-indigo-300 transition" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-500/30 transition transform hover:-translate-y-0.5">
                        Masuk ke Dashboard
                    </button>
                </div>

                <div class="mt-8 text-center border-t border-white/10 pt-6">
                    <p class="text-slate-400 text-sm">Belum punya akun?</p>
                    <a href="{{ route('register') }}" class="text-indigo-400 hover:text-white font-bold text-sm mt-1 inline-block transition">
                        Daftar sebagai Supplier
                    </a>
                </div>
            </form>
        </div>

        <p class="mt-8 text-slate-500 text-sm">&copy; {{ date('Y') }} Gudang Jaya WMS</p>
    </div>
</body>
</html>
