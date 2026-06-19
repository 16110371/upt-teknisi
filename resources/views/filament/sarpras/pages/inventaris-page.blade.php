<x-filament-panels::page>
    @if($this->locationId)
    {{ $this->table }}
    @else
    <div class="text-center py-12 text-slate-500">
        <p>Pilih lokasi dari menu di atas</p>
    </div>
    @endif
</x-filament-panels::page>