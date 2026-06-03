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

    {{-- iOS Splash Screen --}}
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="UPT">
    <link rel="apple-touch-startup-image" href="/images/splash.png">
    <link rel="apple-touch-icon" href="/images/icon-192.png">

    <title>@yield('title', 'UPT - SMK Syubbanul Wathon')</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}" />

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

        [x-cloak] {
            display: none !important;
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
                    Form Perbaikan
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
                <div class="hidden lg:flex items-center gap-3">
                    {{-- ✅ Tombol Scan QR Desktop --}}
                    <button onclick="document.dispatchEvent(new CustomEvent('open-qr-scanner-public'))"
                        class="text-slate-700 hover:text-blue-600 transition" title="Scan QR Unit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </button>

                    <a href="{{ url('/permintaan') }}"
                        class="bg-slate-900 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-800 transition shadow-lg">
                        Lapor
                    </a>
                </div>


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

        {{-- Mobile Dropdown --}}
        <div x-show="open" x-transition class="lg:hidden bg-white border-t border-slate-200 px-6 pb-6 space-y-4">

            <a href="{{ url('/') }}"
                class="block text-sm font-medium {{ request()->is('/') ? 'text-blue-600 font-semibold' : 'text-slate-600' }}">
                Dashboard
            </a>

            <a href="{{ url('/permintaan') }}"
                class="block text-sm font-medium {{ request()->is('permintaan') ? 'text-blue-600 font-semibold' : 'text-slate-600' }}">
                Form Perbaikan
            </a>

            <a href="{{ url('/antrian') }}"
                class="block text-sm font-medium {{ request()->is('antrian') ? 'text-blue-600 font-semibold' : 'text-slate-600' }}">
                Antrian
            </a>

            <a href="{{ url('/permintaan') }}"
                class="block text-center bg-slate-900 text-white py-3 rounded-lg font-semibold hover:bg-slate-800 transition shadow">
                Lapor Kerusakan
            </a>

            {{-- ✅ Tombol Scan QR Mobile --}}
            <button onclick="document.dispatchEvent(new CustomEvent('open-qr-scanner-public'))"
                class="w-full text-center bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition shadow">
                📷 Scan QR
            </button>

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
    {{-- ✅ QR Scanner Modal --}}
    <div x-data="qrScannerPublic()" @open-qr-scanner-public.document="openModal()">

        <div x-show="open" x-transition.opacity
            style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.6); backdrop-filter:blur(4px);"
            @click.self="closeModal()">

            <div x-show="open" x-transition
                style="position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); width:calc(100% - 32px); max-width:28rem; z-index:10000;">

                <div
                    style="background:white; border-radius:16px; overflow:hidden; box-shadow:0 25px 50px rgba(0,0,0,0.3);">

                    <div
                        style="display:flex; align-items:center; justify-content:space-between; padding:16px; border-bottom:1px solid #e5e7eb;">
                        <h2 style="font-weight:bold; color:#111827; font-size:15px;">📷 Scan QR Unit</h2>
                        <button @click="closeModal()"
                            style="width:32px; height:32px; border:none; background:#f3f4f6; border-radius:50%; cursor:pointer; font-size:14px;">
                            ✕
                        </button>
                    </div>

                    <div style="padding:16px;">
                        <div x-show="!unit">
                            <div id="qr-reader-public" style="width:100%; border-radius:12px; overflow:hidden;"></div>
                            {{-- ✅ Tombol aktifkan kamera --}}
                            <div x-show="!permissionGranted && error"
                                style="text-align:center; padding:16px;">
                                <p style="font-size:13px; color:#dc2626; margin-bottom:12px;" x-text="error"></p>
                                <button @click="requestCamera()"
                                    style="padding:12px 24px; background:#2563eb; color:white; border:none; border-radius:12px; font-size:14px; font-weight:600; cursor:pointer; width:100%;">
                                    📷 Aktifkan Kamera
                                </button>
                            </div>
                            <div x-show="error"
                                style="margin-top:12px; background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:16px; text-align:center;">
                                <p style="font-size:24px; margin-bottom:8px;">📷</p>
                                <p style="font-size:13px; font-weight:600; color:#dc2626;" x-text="error"></p>
                                <p style="font-size:11px; color:#ef4444; margin-top:4px;">Buka pengaturan browser →
                                    izinkan akses kamera</p>
                                <button @click="error = ''; startScanner()"
                                    style="margin-top:12px; padding:8px 16px; background:#dc2626; color:white; border:none; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer;">
                                    🔄 Coba Lagi
                                </button>
                            </div>
                        </div>

                        <div x-show="unit" style="display:none;">
                            <div style="background:#f9fafb; border-radius:12px; padding:16px; margin-bottom:12px;">
                                <div
                                    style="display:flex; justify-content:space-between; align-items:flex-start; gap:8px;">
                                    <div>
                                        <p style="font-weight:bold; color:#111827;" x-text="unit?.code"></p>
                                        <p style="font-size:13px; color:#6b7280;"
                                            x-text="unit?.category + ' - ' + unit?.name"></p>
                                        <p style="font-size:11px; color:#9ca3af;" x-text="unit?.location"></p>
                                    </div>
                                    <span
                                        style="padding:4px 10px; border-radius:999px; font-size:11px; font-weight:bold; white-space:nowrap;"
                                        :style="unit?.status === 'good' ? 'background:#dcfce7;color:#166534' : (unit
                                            ?.status === 'broken' ? 'background:#ffedd5;color:#9a3412' :
                                            'background:#fee2e2;color:#991b1b')"
                                        x-text="unit?.status === 'good' ? '✅ Baik' : (unit?.status === 'broken' ? '🔧 Rusak' : '❌ Permanen')">
                                    </span>
                                </div>
                            </div>

                            <div style="display:flex; gap:8px;">
                                <a :href="'/unit/' + unit?.code"
                                    style="flex:1; background:#1e293b; color:white; padding:12px 16px; border-radius:12px; font-size:13px; font-weight:600; text-align:center; text-decoration:none;">
                                    🔗 Lihat Detail & Lapor
                                </a>
                                <button @click="unit = null; startScanner()"
                                    style="flex:1; background:#e5e7eb; color:#374151; padding:12px 16px; border-radius:12px; font-size:13px; font-weight:600; border:none; cursor:pointer;">
                                    📷 Scan Lagi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script> -->
    <script src="https://unpkg.com/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>
        function qrScannerPublic() {
            return {
                open: false,
                unit: null,
                error: '',
                scanner: null,
                stream: null,
                permissionGranted: false,

                openModal() {
                    this.open = true;
                    this.unit = null;
                    this.error = '';
                    this.permissionGranted = false;

                    // Naikkan ke 500ms agar DOM Modal di Android benar-benar selesai merender element #qr-reader-public
                    setTimeout(() => this.startScanner(), 500);
                },

                requestCamera() {
                    this.startScanner();
                },

                closeModal() {
                    this.open = false;
                    this.unit = null;
                    this.stopScanner();
                },

                startScanner() {
                    const el = document.getElementById('qr-reader-public');
                    if (!el) return;

                    // Tambahkan atribut autoplay, loop, muted, playsinline secara eksplisit agar Chrome Android mau memutar stream
                    el.innerHTML = '<video id="qr-video" style="width:100%; border-radius:12px;" autoplay loop muted playsinline></video><canvas id="qr-canvas" style="display:none;"></canvas>';

                    const video = document.getElementById('qr-video');
                    const canvas = document.getElementById('qr-canvas');
                    const ctx = canvas.getContext('2d');

                    const constraints = {
                        video: {
                            facingMode: {
                                ideal: 'environment'
                            },
                            width: {
                                ideal: 640
                            }, // Diturunkan sedikit dari 1280 agar beban render kamera Android lebih ringan saat inisiasi
                            height: {
                                ideal: 480
                            }
                        }
                    };

                    navigator.mediaDevices.getUserMedia(constraints)
                        .then(stream => {
                            this.stream = stream;
                            this.error = '';
                            this.permissionGranted = true;

                            if ('srcObject' in video) {
                                video.srcObject = stream;
                            } else {
                                video.src = window.URL.createObjectURL(stream);
                            }

                            // Android membutuhkan penanganan promise pada fungsi .play()
                            video.play()
                                .then(() => {
                                    this.scanFrame(video, canvas, ctx);
                                })
                                .catch(err => {
                                    console.error("Video play ditangguhkan oleh Chrome:", err);
                                    // Jika autoplay gagal, jalankan scanner lewat trigger klik/interaksi
                                    this.scanFrame(video, canvas, ctx);
                                });
                        })
                        .catch(err => {
                            console.error("Gagal mendeteksi kamera:", err);
                            if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                                this.error = 'Izin kamera ditolak browser atau diblokir oleh sistem pengaturan aplikasi.';
                            } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                                this.error = 'Kamera belakang tidak ditemukan atau sedang digunakan aplikasi lain.';
                            } else {
                                this.error = 'Gagal mengakses kamera: ' + err.message;
                            }
                        });
                },

                scanFrame(video, canvas, ctx) {
                    if (!this.open || this.unit) return;

                    if (video.readyState === video.HAVE_ENOUGH_DATA) {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        const code = jsQR(imageData.data, imageData.width, imageData.height, {
                            inversionAttempts: 'dontInvert',
                        });

                        if (code) {
                            this.stopScanner();
                            this.fetchUnit(code.data);
                            return;
                        }
                    }
                    // Minta frame berikutnya menggunakan requestAnimationFrame (lebih ramah performa Android dibanding setTimeout)
                    requestAnimationFrame(() => this.scanFrame(video, canvas, ctx));
                },

                stopScanner() {
                    if (this.stream) {
                        this.stream.getTracks().forEach(track => track.stop());
                        this.stream = null;
                    }
                },

                async fetchUnit(text) {
                    const code = text.includes('/unit/') ? text.split('/unit/').pop() : text;
                    try {
                        const res = await fetch('/api/unit/' + encodeURIComponent(code));
                        const data = await res.json();
                        if (data.error) {
                            this.error = data.error;
                            this.startScanner();
                        } else {
                            this.unit = data;
                            this.error = '';
                        }
                    } catch (e) {
                        this.error = 'Error memuat data unit';
                        this.startScanner();
                    }
                }
            }
        }
    </script>
    <script type="module">
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
        import {
            getMessaging,
            getToken,
            onMessage
        } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging.js";

        const firebaseConfig = {
            apiKey: "AIzaSyDaVcOL_tbf8A5We8n5lzbnAwAF1eVA4Vg",
            authDomain: "upt-smksw.firebaseapp.com",
            projectId: "upt-smksw",
            storageBucket: "upt-smksw.firebasestorage.app",
            messagingSenderId: "860712318540",
            appId: "1:860712318540:web:099cb74eed0a34191326e9",
            measurementId: "G-E84WX1E4Q5"
        };

        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

        // Request permission
        Notification.requestPermission().then((permission) => {
            if (permission === "granted") {
                getToken(messaging, {
                    vapidKey: "BCg_qkYPP3A0Ju6tnZZI5YrYthuLSEGSCJplM4f9vC8IkFEhfCTRNq1GgbL5QQzIduU6leBeZ0H67orisY1NUyI"
                }).then((currentToken) => {
                    if (currentToken) {
                        console.log("TOKEN:", currentToken);

                        // kirim ke backend Laravel
                        fetch('/save-token', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                token: currentToken
                            })
                        });
                    }
                });
            }
        });

        // Notif saat app dibuka
        onMessage(messaging, (payload) => {
            console.log("Message received:", payload);
            alert(payload.notification.title);
        });
    </script>
</body>

</html>