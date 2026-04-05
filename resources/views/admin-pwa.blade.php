<link rel="manifest" href="/admin-manifest.json">

<meta name="theme-color" content="#111827">

<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="UPT Admin">

<button id="btn-notif" class="bg-blue-600 text-white px-4 py-2 rounded">Aktifkan Notifikasi</button>

<script>
    document.getElementById('btn-notif').addEventListener('click', function() {

        Notification.requestPermission().then(permission => {
            console.log('Permission:', permission);

            if (permission === 'granted') {
                alert('Notifikasi aktif 🎉');
            } else {
                alert('Notifikasi ditolak ❌');
            }
        });

    });
</script>

<link rel="apple-touch-icon" href="/images/admin-icon-192.png">