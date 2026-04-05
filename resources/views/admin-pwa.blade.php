<link rel="manifest" href="/admin-manifest.json">

<meta name="theme-color" content="#111827">

<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="UPT Admin">

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // pastikan ini jalan di PWA (iOS)
        if ('Notification' in window) {

            console.log('Permission awal:', Notification.permission);

            if (Notification.permission === 'default') {

                // iOS BUTUH interaksi user → pakai klik
                document.body.addEventListener('click', function requestNotifOnce() {

                    Notification.requestPermission().then(permission => {
                        console.log('Permission:', permission);

                        if (permission === 'granted') {
                            alert('Notifikasi berhasil diaktifkan 🎉');
                        } else {
                            alert('Notifikasi ditolak');
                        }
                    });

                    // supaya hanya sekali
                    document.body.removeEventListener('click', requestNotifOnce);
                });

            }

        }

    });
</script>

<link rel="apple-touch-icon" href="/images/admin-icon-192.png">