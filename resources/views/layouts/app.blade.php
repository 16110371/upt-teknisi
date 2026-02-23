<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="manifest" href="/manifest.json">

    <meta name="theme-color" content="#0f172a">

    <!-- iOS Support -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="UPT SMK">

    <link rel="apple-touch-icon" href="/images/icon-192.png">


    <title>@yield('title', 'UPT - SMK Syubbanul Wathon')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}" />

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: "Inter", sans-serif;
        }

        .bg-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
    </style>

    @stack('styles')
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="min-h-screen flex flex-col">

    {{-- ===== NAVBAR (shared) ===== --}}
    <nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">

        <div class="container mx-auto px-6 py-4 relative flex items-center justify-between">

            <!-- Logo (Left) -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Syubbanul Wathon"
                    class="h-12 w-12 object-contain rounded-lg" />
                <div>
                    <span class="block text-lg font-bold leading-none text-slate-900">UPT</span>
                    <span class="text-[10px] uppercase tracking-[0.2em] text-slate-500 font-semibold">
                        SMK Syubbanul Wathon
                    </span>
                </div>
            </div>

            <!-- Menu Tengah (Desktop Only) -->
            <div
                class="hidden lg:flex absolute left-1/2 -translate-x-1/2 space-x-8 font-medium text-sm uppercase tracking-wide">

                <a href="{{ url('/') }}"
                    class="transition
                {{ request()->is('/') ? 'text-blue-600 font-semibold' : 'text-slate-600 hover:text-blue-600' }}">
                    Dashboard
                </a>

                <a href="{{ url('/permintaan') }}"
                    class="transition
                {{ request()->is('permintaan') ? 'text-blue-600 font-semibold' : 'text-slate-600 hover:text-blue-600' }}">
                    Form Permintaan
                </a>

                <a href="{{ url('/antrian') }}"
                    class="transition
                {{ request()->is('antrian') ? 'text-blue-600 font-semibold' : 'text-slate-600 hover:text-blue-600' }}">
                    Antrian
                </a>

            </div>

            <!-- Right Side -->
            <div class="flex items-center">

                <!-- Desktop Button -->
                <a href="{{ url('/permintaan') }}"
                    class="hidden lg:inline-block bg-slate-900 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-800 transition shadow-lg">
                    Lapor
                </a>

                <!-- Hamburger (Mobile Only) -->
                <button @click="open = !open" class="lg:hidden ml-4 text-slate-700 focus:outline-none">

                    <!-- Icon -->
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>

                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

            </div>
        </div>

        <!-- Mobile Dropdown -->
        <div x-show="open" x-transition class="lg:hidden bg-white border-t border-slate-200 px-6 pb-6 space-y-4">

            <a href="{{ url('/') }}"
                class="block text-sm font-medium
            {{ request()->is('/') ? 'text-blue-600 font-semibold' : 'text-slate-600' }}">
                Dashboard
            </a>

            <a href="{{ url('/permintaan') }}"
                class="block text-sm font-medium
            {{ request()->is('permintaan') ? 'text-blue-600 font-semibold' : 'text-slate-600' }}">
                Form Permintaan
            </a>

            <a href="{{ url('/antrian') }}"
                class="block text-sm font-medium
            {{ request()->is('antrian') ? 'text-blue-600 font-semibold' : 'text-slate-600' }}">
                Antrian
            </a>

            <a href="{{ url('/permintaan') }}"
                class="block text-center bg-slate-900 text-white py-3 rounded-lg font-semibold hover:bg-slate-800 transition shadow">
                Lapor Kerusakan
            </a>

        </div>

    </nav>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200 py-12">
        <div
            class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center text-sm text-slate-500">
            <p>&copy; {{ date('Y') }} UPT - SMK Syubbanul Wathon.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <p>Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
