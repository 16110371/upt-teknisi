<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
</head>

<body class="bg-pattern text-slate-800">

    {{-- ===== NAVBAR (shared) ===== --}}
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
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
            <div class="hidden lg:flex space-x-8 font-medium text-sm text-slate-600 uppercase tracking-wide">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Dashboard</a>
                <a href="{{ url('/permintaan') }}" class="hover:text-blue-600 transition">Form Permintaan</a>
                <a href="{{ url('/antrian') }}" class="hover:text-blue-600 transition">Antrian</a>
            </div>
            <a href="{{ url('/permintaan') }}"
                class="bg-slate-900 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-800 transition shadow-lg">
                Lapor Kerusakan
            </a>
        </div>
    </nav>

    @yield('content')

    <footer class="bg-white border-t border-slate-200 py-12">
        <div
            class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center text-sm text-slate-500">
            <p>&copy; {{ date('Y') }} UPT - SMK Syubbanul Wathon. Hak Cipta Dilindungi.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-slate-900">Standar Layanan IT</a>
                <a href="#" class="hover:text-slate-900">Inventaris Fasilitas</a>
                <a href="#" class="hover:text-slate-900">Support Teknis</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
