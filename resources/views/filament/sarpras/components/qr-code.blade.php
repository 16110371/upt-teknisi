<div class="flex flex-col items-center gap-3 py-2">
    <img src="data:image/svg+xml;base64,{{ $qrBase64 }}"
        width="150" height="150" alt="QR Code"
        class="rounded-lg border border-slate-200 p-2">

    <a href="{{ route('sarpras.unit.qr', request()->route('id')) }}"
        target="_blank"
        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
        🖨️ Cetak QR Code
    </a>
</div>