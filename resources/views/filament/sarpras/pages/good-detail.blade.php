<x-filament-panels::page>
    {{-- Infolist Detail Barang --}}
    {{ $this->goodInfolist }}

    {{-- Riwayat Alokasi --}}
    <div class="mt-6">
        <p class="text-sm font-semibold text-slate-700 mb-2">📋 Riwayat Alokasi</p>
        {{ $this->table }}
    </div>
</x-filament-panels::page>