<x-filament-panels::page>
    <div class="space-y-4">

        {{-- Info Pengajuan --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <p class="text-sm font-semibold text-blue-800">📋 {{ $this->record->title }}</p>
            <p class="text-xs text-blue-600 mt-1">Diajukan oleh: {{ $this->record->user->name }} · {{ $this->record->created_at->format('d M Y') }}</p>
        </div>

        <form wire:submit="submit">
            {{ $this->form }}

            <div class="flex justify-end mt-6">
                <x-filament::button
                    type="submit"
                    size="lg"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submit">✅ Simpan Realisasi</span>
                    <span wire:loading wire:target="submit">⏳ Menyimpan...</span>
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>