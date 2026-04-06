{{-- Meta tags --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#111827">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="UPT Admin">
<link rel="manifest" href="/admin-manifest.json">
<link rel="apple-touch-icon" href="/images/icon-192-admin.png">

{{-- Tombol hanya untuk iOS --}}
<button id="btn-notif" style="display:none;" class="bg-blue-600 text-white px-4 py-2 rounded">
    Aktifkan Notifikasi
</button>

{{-- Firebase SDK --}}
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js"></script>

<script>
    firebase.initializeApp({
        apiKey: "AIzaSyDaVcOL_tbf8A5We8n5lzbnAwAF1eVA4Vg",
        authDomain: "upt-smksw.firebaseapp.com",
        projectId: "upt-smksw",
        messagingSenderId: "860712318540",
        appId: "1:860712318540:web:099cb74eed0a34191326e9"
    });

    const messaging = firebase.messaging();
    const VAPID_KEY = 'YOUR_VAPID_KEY_HERE'; // ← pastikan sudah diganti

    // ✅ Deteksi platform
    function getPlatform() {
        const ua = navigator.userAgent;
        if (/iPhone|iPad|iPod/.test(ua)) return 'ios';
        if (/Android/.test(ua)) return 'android';
        return 'web';
    }

    // ✅ Register service worker
    async function registerSW() {
        if (!('serviceWorker' in navigator)) return null;
        try {
            const reg = await navigator.serviceWorker.register('/firebase-messaging-sw.js', {
                scope: '/'
            });
            await navigator.serviceWorker.ready;
            console.log('SW ready');
            return reg;
        } catch (err) {
            console.error('SW registration failed:', err);
            return null;
        }
    }

    // ✅ Simpan token ke server
    async function saveTokenToServer(token) {
        const response = await fetch('/save-token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                token: token,
                platform: getPlatform()
            })
        });

        if (response.status === 401) {
            console.log('Belum login, skip simpan token');
            return;
        }

        if (!response.ok) throw new Error('Gagal simpan token');
        return response.json();
    }

    // ✅ Fungsi utama request permission + ambil token
    async function requestNotificationPermission() {
        try {
            const swReg = await registerSW();
            if (!swReg) return;

            const permission = await Notification.requestPermission();
            console.log('Permission:', permission);

            if (permission !== 'granted') return;

            const token = await messaging.getToken({
                vapidKey: VAPID_KEY,
                serviceWorkerRegistration: swReg
            });

            console.log('FCM Token:', token);
            if (!token) return;

            await saveTokenToServer(token);

        } catch (err) {
            console.error('Error:', err);
        }
    }

    // ✅ Init saat halaman load
    document.addEventListener('DOMContentLoaded', async function() {
        const swReg = await registerSW();
        const btn = document.getElementById('btn-notif');
        const platform = getPlatform();

        if (platform === 'ios') {
            // iOS - wajib ada interaksi user
            if (Notification.permission === 'granted') {
                // Sudah pernah aktif - auto refresh token, sembunyikan tombol
                btn.style.display = 'none';
                try {
                    const token = await messaging.getToken({
                        vapidKey: VAPID_KEY,
                        serviceWorkerRegistration: swReg
                    });
                    if (token) await saveTokenToServer(token);
                } catch (err) {
                    console.error('iOS auto token error:', err);
                }
            } else if (Notification.permission === 'denied') {
                // Ditolak - sembunyikan tombol
                btn.style.display = 'none';
            } else {
                // Belum pernah aktif - tampilkan tombol
                btn.style.display = 'block';
            }

        } else if (platform === 'android') {
            // Android - auto request, sembunyikan tombol
            btn.style.display = 'none';
            if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
                await requestNotificationPermission();
            } else if (Notification.permission === 'granted') {
                // Sudah granted - auto refresh token
                try {
                    const token = await messaging.getToken({
                        vapidKey: VAPID_KEY,
                        serviceWorkerRegistration: swReg
                    });
                    if (token) await saveTokenToServer(token);
                } catch (err) {
                    console.error('Android auto token error:', err);
                }
            }

        } else {
            // PC/Desktop - sembunyikan tombol sama sekali
            btn.style.display = 'none';
        }
    });

    // ✅ Klik tombol - khusus iOS
    document.getElementById('btn-notif').addEventListener('click', async function() {
        await requestNotificationPermission();
        // Sembunyikan tombol setelah klik
        this.style.display = 'none';
    });
</script>