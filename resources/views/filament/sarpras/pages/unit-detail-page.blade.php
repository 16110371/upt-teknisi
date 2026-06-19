<x-filament-panels::page>
    {{-- Info Unit --}}
    {{ $this->unitInfolist }}

    {{-- Riwayat Kerusakan dari UPT --}}
    <div class="mt-6">
        <p class="text-sm font-semibold text-slate-700 mb-2">🔧 Riwayat Kerusakan (dari UPT)</p>
        {{ $this->table }}
    </div>
</x-filament-panels::page>