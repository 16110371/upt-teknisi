<script type="module">
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
    import {
        getMessaging,
        getToken
    } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging.js";

    console.log("🔥 Firebase Loaded");

    const firebaseConfig = {
        apiKey: "AIzaSyDaVcOL_tbf8A5We8n5lzbnAwAF1eVA4Vg",
        authDomain: "upt-smksw.firebaseapp.com",
        projectId: "upt-smksw",
        messagingSenderId: "860712318540",
        appId: "1:860712318540:web:099cb74eed0a34191326e9"
    };

    const app = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);

    Notification.requestPermission().then((permission) => {
        console.log("Permission:", permission);

        if (permission === "granted") {
            getToken(messaging, {
                vapidKey: "BCg_qkYPP3A0Ju6tnZZI5YrYthuLSEGSCJplM4f9vC8IkFEhfCTRNq1GgbL5QQzIduU6leBeZ0H67orisY1NUyI"
            }).then((token) => {
                console.log("TOKEN:", token);
            });
        }
    });
</script>