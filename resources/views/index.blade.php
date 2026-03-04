@extends('layouts.app')

@section('title', 'UPT - SMK Syubbanul Wathon')

@section('content')


    {{-- ===== HERO SECTION ===== --}}
    <header class="py-20 lg:py-32 overflow-hidden">
        <div class="container mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-5xl lg:text-6xl font-extrabold text-slate-900 leading-[1.1] mb-6">
                    Fasilitas IT Sekolah
                    <span class="text-blue-600 italic">Berkinerja Maksimal</span>
                </h1>
                <p class="text-lg text-slate-600 mb-10 max-w-lg leading-relaxed">
                    Portal manajemen infrastruktur IT SMK Syubbanul Wathon.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ url('/permintaan') }}"
                        class="bg-blue-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-blue-700 transition shadow-xl shadow-blue-200">
                        Ajukan Tiket Perbaikan
                    </a>
                </div>
            </div>

            <div class="relative">
                <div class="absolute inset-0 bg-blue-500 rounded-3xl rotate-3 opacity-10"></div>
                <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=800&q=80"
                    class="rounded-3xl shadow-2xl relative z-10 grayscale hover:grayscale-0 transition duration-500" />
            </div>
        </div>
    </header>

    <section class="bg-white border-y border-slate-200 py-16">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-12">
                <div class="text-center border-r border-slate-100 last:border-0">
                    <span class="block text-4xl font-bold text-slate-900 mb-1">240+</span>
                    <span class="text-xs uppercase tracking-widest text-slate-500 font-bold">Unit Perangkat</span>
                </div>
                <div class="text-center border-r border-slate-100 last:border-0">
                    <span class="block text-4xl font-bold text-blue-600 mb-1">12 Ruang</span>
                    <span class="text-xs uppercase tracking-widest text-slate-500 font-bold">Fasilitas IT</span>
                </div>
                <div class="text-center border-r border-slate-100 last:border-0">
                    <span class="block text-4xl font-bold text-slate-900 mb-1">95%</span>
                    <span class="text-xs uppercase tracking-widest text-slate-500 font-bold">Operasional Harian</span>
                </div>
                <div class="text-center border-r border-slate-100 last:border-0">
                    <span class="block text-4xl font-bold text-green-600 mb-1">4h</span>
                    <span class="text-xs uppercase tracking-widest text-slate-500 font-bold">Respon Perbaikan</span>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="py-24">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900 mb-4">
                        Layanan Fasilitas IT Sekolah
                    </h2>
                    <p class="text-slate-500 max-w-md italic border-l-4 border-blue-600 pl-4">
                        Dukungan teknis profesional untuk semua infrastruktur IT sekolah,
                        dari lab programming hingga sistem jaringan terintegrasi.
                    </p>
                </div>
                <a href="#" class="text-blue-600 font-bold flex items-center hover:underline">Semua Layanan <span
                        class="ml-2">→</span></a>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div
                    class="bg-white p-10 rounded-2xl shadow-sm border border-slate-100 hover:border-blue-400 transition-all duration-300">
                    <div class="mb-6 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.5 1h3m-3 14.318C7.239 15.789 5.684 17.899 5.684 20.25c0 2.485 2.134 4.5 4.766 4.5.342 0 .681-.021 1.023-.062m-3.023-14.562L21 3m-9.5 0c0-1.104.896-2 2-2s2 .896 2 2M5.684 20.25H2.036A2.036 2.036 0 010 18.214v-1.28c0-.529.215-1.036.563-1.404.369-.378.884-.595 1.473-.595h15.928c.589 0 1.104.217 1.473.595.348.368.563.875.563 1.404v1.28A2.036 2.036 0 0121.964 20.25H18.316m-7.632-8.25a2.75 2.75 0 100-5.5 2.75 2.75 0 000 5.5z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Perbaikan Perangkat IT</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Servis penuh terhadap semua perangkat di fasilitas IT sekolah:
                        komputer lab, server, printer jaringan, projector, dan peralatan
                        pendukung pembelajaran digital lainnya.
                    </p>
                </div>

                <div
                    class="bg-white p-10 rounded-2xl shadow-sm border border-slate-100 hover:border-blue-400 transition-all duration-300">
                    <div class="mb-6 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Setup Infrastruktur IT</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Pemasangan dan konfigurasi sistem operasi terbaru, aplikasi khusus
                        pembelajaran, pembaruan driver, security patches, dan optimasi
                        performa perangkat di seluruh fasilitas IT sekolah.
                    </p>
                </div>

                <div
                    class="bg-white p-10 rounded-2xl shadow-sm border border-slate-100 hover:border-blue-400 transition-all duration-300">
                    <div class="mb-6 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.111 16.251a.75.75 0 0 0 .75.75h6.278a.75.75 0 0 0 .75-.75M12.25 3.75a5.25 5.25 0 1 0 0 10.5 5.25 5.25 0 0 0 0-10.5zM12 12.75v-6m3 3h-6" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Jaringan & Sistem Server</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Pengelolaan jaringan LAN/WAN, WiFi sekolah, sistem server pusat,
                        backup data, keamanan cyber, dan monitoring infrastruktur IT 24/7
                        untuk menjaga konektivitas dan integritas data.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="container mx-auto px-6 mb-24">
        <div class="bg-slate-900 rounded-[32px] p-12 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/20 blur-3xl rounded-full"></div>
            <h2 class="text-3xl font-bold text-white mb-6 relative z-10">
                Sistem Ticketing Perbaikan Fasilitas IT
            </h2>
            <p class="text-slate-400 mb-10 max-w-2xl mx-auto relative z-10 uppercase tracking-widest text-sm">
                Laporkan gangguan perangkat dan infrastruktur IT secara real-time
                untuk penanganan cepat dan terdokumentasi.
            </p>
            <a href="#"
                class="inline-block bg-white text-slate-900 px-10 py-4 rounded-xl font-bold hover:scale-105 transition-all shadow-xl">Ajukan
                Tiket Perbaikan</a>
        </div>
    </section>

@endsection
