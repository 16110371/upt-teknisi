importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyDaVcOL_tbf8A5We8n5lzbnAwAF1eVA4Vg",
    authDomain: "upt-smksw.firebaseapp.com",
    projectId: "upt-smksw",
    messagingSenderId: "860712318540",
    appId: "1:860712318540:web:099cb74eed0a34191326e9"
});

const messaging = firebase.messaging();

// ✅ Flag untuk cegah duplikat
let isHandled = false;

// ✅ Native push event - handle DULUAN sebelum Firebase SDK
self.addEventListener('push', function (event) {
    console.log('[SW] Native push event:', event);
    isHandled = true; // tandai sudah dihandle

    let title   = 'Notifikasi Baru';
    let options = {
        body  : '',
        icon  : '/logo.png',
        badge : '/logo.png',
        data  : { url: '/admin/requests' }
    };

    if (event.data) {
        try {
            const payload = event.data.json();
            title            = payload.notification?.title || payload.data?.title || title;
            options.body     = payload.notification?.body  || payload.data?.body  || '';
            options.data.url = payload.data?.url || '/admin/requests';
        } catch (e) {
            console.error('[SW] Push parse error:', e);
        }
    }

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

// ✅ onBackgroundMessage hanya jalan kalau push event belum handle
messaging.onBackgroundMessage(function (payload) {
    console.log('[SW] onBackgroundMessage:', payload);

    if (isHandled) {
        console.log('[SW] Sudah dihandle oleh push event, skip.');
        isHandled = false; // reset flag
        return;
    }

    const title   = payload.notification?.title || payload.data?.title || 'Notifikasi';
    const options = {
        body  : payload.notification?.body || payload.data?.body || '',
        icon  : '/logo.png',
        badge : '/logo.png',
        data  : { url: payload.data?.url || '/admin/requests' }
    };

    self.registration.showNotification(title, options);
});

// ✅ Handle klik notifikasi
self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const url = event.notification.data?.url || '/admin/requests';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then(function (clientList) {
                for (const client of clientList) {
                    if (client.url.includes('/admin') && 'focus' in client) {
                        client.focus();
                        return client.navigate(url);
                    }
                }
                return clients.openWindow(url);
            })
    );
});