<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="flex justify-end mt-8 gap-3">
            <x-filament::button
                tag="a"
                href="{{ $this->getBackUrl() }}"
                color="gray">
                Batal
            </x-filament::button>

            <x-filament::button
                type="submit"
                size="lg"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-75 cursor-not-allowed">
                <span wire:loading.remove wire:target="save">💾 Simpan Perubahan</span>
                <span wire:loading wire:target="save">⏳ Menyimpan...</span>
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>