<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}

        <div class="flex justify-end mt-6">
            <x-filament::button
                type="submit"
                size="lg"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-75 cursor-not-allowed">
                <span wire:loading.remove wire:target="submit">✅ Simpan Pengecekan</span>
                <span wire:loading wire:target="submit">⏳ Menyimpan...</span>
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>