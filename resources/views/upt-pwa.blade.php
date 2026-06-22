{{-- Meta tags --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#1e3a5f">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="UPT SMK SW">
<link rel="manifest" href="/upt-manifest.json">
<link rel="apple-touch-icon" href="/images/icon-192-admin.png">
<link rel="apple-touch-startup-image" href="/images/splash.png">

{{-- Tombol hanya untuk iOS --}}
<button id="btn-notif-upt" style="display:none;" class="bg-blue-600 text-white px-4 py-2 rounded">
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

    const messagingUpt = firebase.messaging();
    const VAPID_KEY_UPT = 'BCg_qkYPP3A0Ju6tnZZI5YrYthuLSEGSCJplM4f9vC8IkFEhfCTRNq1GgbL5QQzIduU6leBeZ0H67orisY1NUyI';

    function getPlatformUpt() {
        const ua = navigator.userAgent;
        if (/iPhone|iPad|iPod/.test(ua)) return 'ios';
        if (/Android/.test(ua)) return 'android';
        return 'web';
    }

    async function registerSWUpt() {
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

    async function saveTokenToServerUpt(token) {
        const response = await fetch('/save-token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                token: token,
                platform: getPlatformUpt()
            })
        });

        if (response.status === 401) return;
        if (!response.ok) throw new Error('Gagal simpan token');
        return response.json();
    }

    async function requestNotificationPermissionUpt() {
        try {
            const swReg = await registerSWUpt();
            if (!swReg) return;

            const permission = await Notification.requestPermission();
            if (permission !== 'granted') return;

            const token = await messagingUpt.getToken({
                vapidKey: VAPID_KEY_UPT,
                serviceWorkerRegistration: swReg
            });

            if (!token) return;
            await saveTokenToServerUpt(token);

        } catch (err) {
            console.error('Error:', err);
        }
    }

    document.addEventListener('DOMContentLoaded', async function() {
        const swReg = await registerSWUpt();
        const btn = document.getElementById('btn-notif-upt');
        const platform = getPlatformUpt();

        if (platform === 'ios') {
            if (Notification.permission === 'granted') {
                btn.style.display = 'none';
                try {
                    const token = await messagingUpt.getToken({
                        vapidKey: VAPID_KEY_UPT,
                        serviceWorkerRegistration: swReg
                    });
                    if (token) await saveTokenToServerUpt(token);
                } catch (err) {
                    console.error('iOS auto token error:', err);
                }
            } else if (Notification.permission === 'denied') {
                btn.style.display = 'none';
            } else {
                btn.style.display = 'block';
            }

        } else if (platform === 'android') {
            btn.style.display = 'none';
            if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
                await requestNotificationPermissionUpt();
            } else if (Notification.permission === 'granted') {
                try {
                    const token = await messagingUpt.getToken({
                        vapidKey: VAPID_KEY_UPT,
                        serviceWorkerRegistration: swReg
                    });
                    if (token) await saveTokenToServerUpt(token);
                } catch (err) {
                    console.error('Android auto token error:', err);
                }
            }

        } else {
            btn.style.display = 'none';
        }
    });

    document.getElementById('btn-notif-upt').addEventListener('click', async function() {
        await requestNotificationPermissionUpt();
        this.style.display = 'none';
    });
</script>