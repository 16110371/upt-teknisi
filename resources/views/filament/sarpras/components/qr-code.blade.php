<div class="flex flex-col items-center py-2" style="gap: 24px !important;"> <img src="data:image/svg+xml;base64,{{ $qrBase64 }}"
        width="150" height="150" alt="QR Code"
        class="rounded-lg border border-slate-200 p-2">

    <x-filament::button
        tag="a"
        href="{{ route('sarpras.unit.qr', request()->route('id')) }}"
        target="_blank"
        color="success"
        icon="heroicon-m-printer"
        size="sm"
        class="shadow-sm hover:translate-y-[-1px] transition-transform duration-200">
        Cetak QR Code
    </x-filament::button>
</div>