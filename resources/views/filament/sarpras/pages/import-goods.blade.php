<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Info format --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <p class="text-sm font-semibold text-blue-800 mb-2">📋 Format Kolom Excel</p>
            <p class="text-xs text-blue-600 mb-3">Baris pertama harus berisi nama kolom berikut:</p>

            <div class="overflow-x-auto">
                <table class="text-xs text-blue-700 border-collapse w-full">
                    <thead>
                        <tr class="bg-blue-100">
                            <th class="border border-blue-300 px-2 py-1">kategori</th>
                            <th class="border border-blue-300 px-2 py-1">kode_jenis</th>
                            <th class="border border-blue-300 px-2 py-1">nama_barang</th>
                            <th class="border border-blue-300 px-2 py-1">spesifikasi</th>
                            <th class="border border-blue-300 px-2 py-1">merk</th>
                            <th class="border border-blue-300 px-2 py-1">jumlah</th>
                            <th class="border border-blue-300 px-2 py-1">satuan</th>
                            <th class="border border-blue-300 px-2 py-1">harga</th>
                            <th class="border border-blue-300 px-2 py-1">tanggal_beli</th>
                            <th class="border border-blue-300 px-2 py-1">tahun</th>
                            <th class="border border-blue-300 px-2 py-1">supplier</th>
                            <th class="border border-blue-300 px-2 py-1">habis_pakai</th>
                            <th class="border border-blue-300 px-2 py-1">sumber_dana</th>
                            <th class="border border-blue-300 px-2 py-1">catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-blue-200 px-2 py-1">A</td>
                            <td class="border border-blue-200 px-2 py-1">A10</td>
                            <td class="border border-blue-200 px-2 py-1">Monitor LG 22"</td>
                            <td class="border border-blue-200 px-2 py-1">22 inch FHD</td>
                            <td class="border border-blue-200 px-2 py-1">LG</td>
                            <td class="border border-blue-200 px-2 py-1">24</td>
                            <td class="border border-blue-200 px-2 py-1">unit</td>
                            <td class="border border-blue-200 px-2 py-1">1500000</td>
                            <td class="border border-blue-200 px-2 py-1">2022-01-15</td>
                            <td class="border border-blue-200 px-2 py-1">2022</td>
                            <td class="border border-blue-200 px-2 py-1">Toko ABC</td>
                            <td class="border border-blue-200 px-2 py-1">tidak</td>
                            <td class="border border-blue-200 px-2 py-1">BOS</td>
                            <td class="border border-blue-200 px-2 py-1">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Download template --}}
            <div class="mt-3">
                <a href="{{ route('sarpras.goods.template') }}"
                    class="text-sm text-blue-700 font-semibold hover:underline">
                    📥 Download Template Excel
                </a>
            </div>
        </div>

        {{-- Form Upload --}}
        <form wire:submit="import">
            {{ $this->form }}

            <div class="flex justify-end mt-4">
                <x-filament::button
                    type="submit"
                    size="lg"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-75 cursor-not-allowed">
                    <span wire:loading.remove wire:target="import">📤 Import Sekarang</span>
                    <span wire:loading wire:target="import">⏳ Mengimport...</span>
                </x-filament::button>
            </div>
        </form>

    </div>
</x-filament-panels::page>