{{-- Meta tags --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#111827">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="UPT Admin">
<link rel="manifest" href="/admin-manifest.json">
<link rel="apple-touch-icon" href="/images/icon-192-admin.png">

{{-- Tombol --}}
<button id="btn-notif" class="bg-blue-600 text-white px-4 py-2 rounded">
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
    const VAPID_KEY = 'BCg_qkYPP3A0Ju6tnZZI5YrYthuLSEGSCJplM4f9vC8IkFEhfCTRNq1GgbL5QQzIduU6leBeZ0H67orisY1NUyI'; // ← ganti dengan VAPID key dari Firebase Console

    // Deteksi platform
    function getPlatform() {
        const ua = navigator.userAgent;
        if (/iPhone|iPad|iPod/.test(ua)) return 'ios';
        if (/Android/.test(ua)) return 'android';
        return 'web';
    }

    // Register service worker
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

    // Simpan token ke server
    async function saveTokenToServer(token) {
        const response = await fetch('/save-token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                token: token,
                platform: getPlatform() // ✅ kirim platform
            })
        });

        if (!response.ok) throw new Error('Gagal simpan token');
        return response.json();
    }

    // Update tampilan tombol
    function updateButton(status) {
        const btn = document.getElementById('btn-notif');
        const states = {
            granted: {
                text: '✅ Notifikasi Aktif',
                cls: 'bg-green-600'
            },
            denied: {
                text: '❌ Notifikasi Diblokir',
                cls: 'bg-red-600'
            },
            default: {
                text: 'Aktifkan Notifikasi',
                cls: 'bg-blue-600'
            },
        };
        const s = states[status] || states.default;
        btn.textContent = s.text;
        btn.className = btn.className.replace(/bg-\w+-600/, s.cls);
    }

    // Fungsi utama
    async function requestNotificationPermission() {
        try {
            const swReg = await registerSW();
            if (!swReg) {
                alert('Browser tidak support notifikasi ❌');
                return;
            }

            const permission = await Notification.requestPermission();
            console.log('Permission:', permission);

            if (permission !== 'granted') {
                updateButton('denied');
                alert('Notifikasi ditolak ❌');
                return;
            }

            const token = await messaging.getToken({
                vapidKey: VAPID_KEY,
                serviceWorkerRegistration: swReg
            });

            console.log('FCM Token:', token);

            if (!token) {
                alert('Gagal mendapatkan token ❌');
                return;
            }

            await saveTokenToServer(token);
            updateButton('granted');
            alert('Notifikasi aktif 🎉');

        } catch (err) {
            console.error('Error:', err);
            alert('Terjadi error: ' + err.message);
        }
    }

    // Init saat halaman load
    document.addEventListener('DOMContentLoaded', async function() {
        await registerSW();
        updateButton(Notification.permission);
    });

    document.getElementById('btn-notif').addEventListener('click', requestNotificationPermission);
</script>