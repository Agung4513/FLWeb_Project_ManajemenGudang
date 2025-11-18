<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Gudang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    @if (Auth::check())
        <nav class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <a href="{{ auth()->user()->getDashboardRoute() }}" class="flex-shrink-0 flex items-center">
                            <span class="text-xl font-bold text-gray-900">Manajemen Gudang</span>
                        </a>
                    </div>
                    <div class="flex items-center">
                        <div class="ml-4 flex items-center md:ml-6">
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    @endif

    <div class="min-h-screen flex">
        @yield('sidebar')
        <div class="flex-1">
            @yield('content')
        </div>
    </div>
</body>
</html>
