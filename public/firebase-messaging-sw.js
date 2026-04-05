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

messaging.onBackgroundMessage(function (payload) {
    console.log('Payload:', payload);

    const title = payload.data.title;
    const options = {
        body: payload.data.body,
        icon: '/logo.png',
        data: {
            url: payload.data.url
        }
    };

    self.registration.showNotification(title, options);
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    let url = '/admin/requests';

    if (event.notification.data && event.notification.data.url) {
        url = event.notification.data.url;
    }

    event.waitUntil(
        clients.openWindow(url)
    );
});
