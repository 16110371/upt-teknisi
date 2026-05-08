<div>

    {{-- Tombol Scan di Topbar --}}
    <button
        wire:click="openScanner"
        class="flex items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition"
        title="Scan QR Unit">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        </svg>
    </button>

    {{-- Modal Scanner --}}
    @if ($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
        wire:click.self="closeScanner">

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">

            {{-- Header Modal --}}
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="font-bold text-gray-900 dark:text-white">📷 Scan QR Unit</h2>
                <button wire:click="closeScanner"
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                    ✕
                </button>
            </div>

            <div class="p-4 space-y-4">

                {{-- Scanner --}}
                @if (!$unit)
                <div id="qr-reader" class="w-full rounded-xl overflow-hidden"></div>

                @if ($error)
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-600">
                    {{ $error }}
                </div>
                @endif
                @endif

                {{-- Hasil Scan --}}
                @if ($unit)
                <div class="space-y-3">

                    {{-- Info Unit --}}
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white">{{ $unit['code'] }}</p>
                                <p class="text-sm text-gray-500">{{ $unit['category'] }} - {{ $unit['name'] }}
                                </p>
                                <p class="text-xs text-gray-400">{{ $unit['location'] }}</p>
                            </div>
                            <span
                                class="px-2 py-1 rounded-full text-xs font-bold
                                {{ $unit['status'] === 'good'
                                    ? 'bg-green-100 text-green-700'
                                    : ($unit['status'] === 'broken'
                                        ? 'bg-orange-100 text-orange-700'
                                        : 'bg-red-100 text-red-700') }}">
                                {{ $unit['status'] === 'good' ? '✅ Baik' : ($unit['status'] === 'broken' ? '🔧 Rusak' : '❌ Permanen') }}
                            </span>
                        </div>

                        @if ($unit['note'])
                        <p class="text-xs text-gray-500 mt-2">{{ $unit['note'] }}</p>
                        @endif
                    </div>

                    {{-- Riwayat --}}
                    @if (!empty($unit['logs']))
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-2">Riwayat Terakhir:</p>
                        <div class="space-y-2 max-h-40 overflow-y-auto">
                            @foreach ($unit['logs'] as $log)
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-2.5 text-xs">
                                <div class="flex justify-between">
                                    <span
                                        class="font-semibold
                                        {{ $log['type'] === 'rusak' ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $log['type'] === 'rusak' ? '🔧 Rusak' : '✅ Selesai' }}
                                    </span>
                                    <span class="text-gray-400">{{ $log['created_at'] }}</span>
                                </div>
                                @if ($log['note'])
                                <p class="text-gray-600 mt-1">{{ $log['note'] }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Tombol Aksi --}}
                    <div class="flex gap-2">
                        <a href="{{ url('/unit/' . $unit['code']) }}" target="_blank"
                            class="flex-1 bg-blue-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold text-center hover:bg-blue-700 transition">
                            🔗 Buka Detail
                        </a>
                        <button wire:click="openScanner"
                            class="flex-1 bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-300 transition">
                            📷 Scan Lagi
                        </button>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Script QR Scanner --}}
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        document.addEventListener('livewire:initialized', function() {
            startScanner();
        });

        function startScanner() {
            const readerEl = document.getElementById('qr-reader');
            if (!readerEl) return;

            const html5QrCode = new Html5Qrcode("qr-reader");

            html5QrCode.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                },
                (decodedText) => {
                    html5QrCode.stop();
                    @this.processQr(decodedText);
                },
                (error) => {}
            ).catch(err => {
                console.error('Camera error:', err);
            });
        }

        // ✅ Restart scanner saat klik "Scan Lagi"
        document.addEventListener('livewire:updated', function() {
            setTimeout(startScanner, 100);
        });
    </script>
    @endif
</div>