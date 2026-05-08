<div x-data="qrScanner()">
    {{-- Tombol --}}
    <button
        @click="openModal()"
        style="background:transparent; border:none; cursor:pointer; padding:4px; display:flex; align-items:center; justify-content:center;"
        title="Scan QR Unit">
        <svg class="fi-icon fi-size-lg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
        </svg>
    </button>

    {{-- Modal --}}
    <div x-show="open"
        x-transition
        style="display:none;"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
        @click.self="closeModal()">

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="font-bold text-gray-900 dark:text-white">📷 Scan QR Unit</h2>
                <button @click="closeModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400">
                    ✕
                </button>
            </div>

            <div class="p-4 space-y-4">

                {{-- Scanner --}}
                <div x-show="!unit">
                    <div id="qr-reader" class="w-full rounded-xl overflow-hidden"></div>
                    {{-- Error kamera --}}
                    <div x-show="error"
                        class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
                        <p class="text-2xl mb-2">📷</p>
                        <p class="text-sm font-semibold text-red-600" x-text="error"></p>
                        <p class="text-xs text-red-400 mt-1">
                            Buka pengaturan browser → izinkan akses kamera
                        </p>
                        <button @click="error = ''; startScanner()"
                            class="mt-3 px-4 py-2 bg-red-600 text-white rounded-lg text-xs font-semibold hover:bg-red-700 transition">
                            🔄 Coba Lagi
                        </button>
                    </div>
                </div>

                {{-- Hasil Scan --}}
                <div x-show="unit" class="space-y-3">
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white" x-text="unit?.code"></p>
                                <p class="text-sm text-gray-500" x-text="unit?.category + ' - ' + unit?.name"></p>
                                <p class="text-xs text-gray-400" x-text="unit?.location"></p>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-bold"
                                :class="{
                                    'bg-green-100 text-green-700': unit?.status === 'good',
                                    'bg-orange-100 text-orange-700': unit?.status === 'broken',
                                    'bg-red-100 text-red-700': unit?.status === 'permanent_broken'
                                }"
                                x-text="unit?.status === 'good' ? '✅ Baik' : (unit?.status === 'broken' ? '🔧 Rusak' : '❌ Permanen')">
                            </span>
                        </div>
                    </div>

                    {{-- Riwayat --}}
                    <template x-if="unit?.logs?.length > 0">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 mb-2">Riwayat Terakhir:</p>
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                <template x-for="log in unit.logs" :key="log.created_at">
                                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-2.5 text-xs">
                                        <div class="flex justify-between">
                                            <span class="font-semibold"
                                                :class="log.type === 'rusak' ? 'text-red-600' : 'text-green-600'"
                                                x-text="log.type === 'rusak' ? '🔧 Rusak' : '✅ Selesai'">
                                            </span>
                                            <span class="text-gray-400" x-text="log.created_at"></span>
                                        </div>
                                        <p class="text-gray-600 mt-1" x-text="log.note"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Tombol Aksi --}}
                    <div class="flex gap-2">
                        <a :href="'/unit/' + unit?.code"
                            target="_blank"
                            class="flex-1 bg-blue-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold text-center hover:bg-blue-700 transition">
                            🔗 Buka Detail
                        </a>
                        <button @click="unit = null; startScanner()"
                            class="flex-1 bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-300 transition">
                            📷 Scan Lagi
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- QR Scanner Library --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    function qrScanner() {
        return {
            open: false,
            unit: null,
            error: '',
            scanner: null,

            openModal() {
                this.open = true;
                this.unit = null;
                this.error = '';
                this.$nextTick(() => this.startScanner());
            },

            closeModal() {
                this.open = false;
                this.unit = null;
                this.stopScanner();
            },

            startScanner() {
                const el = document.getElementById('qr-reader');
                if (!el) return;

                this.stopScanner();

                this.scanner = new Html5Qrcode('qr-reader');
                this.scanner.start({
                        facingMode: 'environment'
                    }, {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },
                    async (text) => {
                            await this.stopScanner();
                            await this.fetchUnit(text);
                        },
                        () => {}
                ).catch(err => {
                    this.error = 'Tidak dapat mengakses kamera. Pastikan izin kamera sudah diberikan.';
                });
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
                // Ekstrak kode dari URL kalau scan URL
                const code = text.includes('/unit/') ?
                    text.split('/unit/').pop() :
                    text;

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