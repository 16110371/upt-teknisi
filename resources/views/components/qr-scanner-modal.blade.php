<div x-data="qrScanner()" @open-qr-scanner.document="openModal()" style="position:relative; z-index:9999;">

    {{-- Modal --}}
    <div x-show="open" x-transition.opacity
        style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.6); backdrop-filter:blur(4px);"
        @click.self="closeModal()">

        <div x-show="open" x-transition
            style="position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); width:100%; max-width:28rem; z-index:10000;">

            <div style="background:white; border-radius:16px; overflow:hidden; box-shadow:0 25px 50px rgba(0,0,0,0.3);">

                {{-- Header --}}
                <div
                    style="display:flex; align-items:center; justify-content:space-between; padding:16px; border-bottom:1px solid #e5e7eb;">
                    <h2 style="font-weight:bold; color:#111827;">📷 Scan QR Unit</h2>
                    <button @click="closeModal()"
                        style="width:32px; height:32px; border:none; background:#f3f4f6; border-radius:50%; cursor:pointer; font-size:14px;">
                        ✕
                    </button>
                </div>

                <div style="padding:16px;">

                    {{-- Scanner --}}
                    <div x-show="!unit">
                        <div id="qr-reader" style="width:100%; border-radius:12px; overflow:hidden;"></div>

                        {{-- Error --}}
                        <div x-show="error"
                            style="margin-top:12px; background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:16px; text-align:center;">
                            <p style="font-size:24px; margin-bottom:8px;">📷</p>
                            <p style="font-size:13px; font-weight:600; color:#dc2626;" x-text="error"></p>
                            <p style="font-size:11px; color:#ef4444; margin-top:4px;">
                                Buka pengaturan browser → izinkan akses kamera
                            </p>
                            <button @click="error = ''; startScanner()"
                                style="margin-top:12px; padding:8px 16px; background:#dc2626; color:white; border:none; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer;">
                                🔄 Coba Lagi
                            </button>
                        </div>
                    </div>

                    {{-- Hasil Scan --}}
                    <div x-show="unit" style="display:none;">
                        <div style="background:#f9fafb; border-radius:12px; padding:16px; margin-bottom:12px;">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                                <div>
                                    <p style="font-weight:bold; color:#111827;" x-text="unit?.code"></p>
                                    <p style="font-size:13px; color:#6b7280;"
                                        x-text="unit?.category + ' - ' + unit?.name"></p>
                                    <p style="font-size:11px; color:#9ca3af;" x-text="unit?.location"></p>
                                </div>
                                <span style="padding:4px 10px; border-radius:999px; font-size:11px; font-weight:bold;"
                                    :style="unit?.status === 'good' ? 'background:#dcfce7;color:#166534' : (unit
                                        ?.status === 'broken' ? 'background:#ffedd5;color:#9a3412' :
                                        'background:#fee2e2;color:#991b1b')"
                                    x-text="unit?.status === 'good' ? '✅ Baik' : (unit?.status === 'broken' ? '🔧 Rusak' : '❌ Permanen')">
                                </span>
                            </div>
                        </div>

                        {{-- Riwayat --}}
                        <template x-if="unit?.logs?.length > 0">
                            <div style="margin-bottom:12px;">
                                <p style="font-size:11px; font-weight:600; color:#6b7280; margin-bottom:8px;">Riwayat
                                    Terakhir:</p>
                                <div
                                    style="max-height:160px; overflow-y:auto; display:flex; flex-direction:column; gap:8px;">
                                    <template x-for="log in unit.logs" :key="log.created_at">
                                        <div
                                            style="background:#f9fafb; border-radius:8px; padding:10px; font-size:11px;">
                                            <div style="display:flex; justify-content:space-between;">
                                                <span
                                                    :style="log.type === 'rusak' ? 'color:#dc2626;font-weight:600' :
                                                        'color:#16a34a;font-weight:600'"
                                                    x-text="log.type === 'rusak' ? '🔧 Rusak' : '✅ Selesai'">
                                                </span>
                                                <span style="color:#9ca3af;" x-text="log.created_at"></span>
                                            </div>
                                            <p style="color:#4b5563; margin-top:4px;" x-text="log.note"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        {{-- Tombol --}}
                        <div style="display:flex; gap:8px;">
                            <a :href="'/unit/' + unit?.code" target="_blank"
                                style="flex:1; background:#2563eb; color:white; padding:10px 16px; border-radius:12px; font-size:13px; font-weight:600; text-align:center; text-decoration:none;">
                                🔗 Buka Detail
                            </a>
                            <button @click="unit = null; startScanner()"
                                style="flex:1; background:#e5e7eb; color:#374151; padding:10px 16px; border-radius:12px; font-size:13px; font-weight:600; border:none; cursor:pointer;">
                                📷 Scan Lagi
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    function qrScanner() {
        return {
            open: false,
            unit: null,
            error: '',
            scanner: null,

            async openModal() {
                this.open = true;
                try {
                    // Coba pancing dengan resolusi minimal
                    await navigator.mediaDevices.getUserMedia({
                        video: true
                    });
                    this.startScanner();
                } catch (err) {
                    alert("Pesan Error: " + err.name);
                    // Jika muncul NotAllowedError di sini, berarti masalah 100% ada di setting HP/Browser, bukan kode.
                }
            },


            stopScanner() {
                if (this.scanner) {
                    return this.scanner.stop().catch(() => {}).finally(() => {
                        this.scanner = null;
                    });
                }
                return Promise.resolve();
            },

            async fetchUnit(text) {
                alert('Fetching: ' + text); // ✅ debug sementara
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
