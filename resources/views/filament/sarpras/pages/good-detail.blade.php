<x-filament-panels::page>
    {{-- Info Barang --}}
    <div class="bg-white border border-slate-200 rounded-xl p-6 mb-4 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <p class="text-xs text-slate-500">Kode Jenis</p>
            <p class="font-bold text-slate-900">{{ $this->good->code }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500">Nama Barang</p>
            <p class="font-bold text-slate-900">{{ $this->good->name }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500">Merk</p>
            <p class="font-bold text-slate-900">{{ $this->good->brand ?? '-' }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500">Spesifikasi</p>
            <p class="font-bold text-slate-900">{{ $this->good->specification ?? '-' }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500">Total Unit</p>
            <p class="font-bold text-slate-900">{{ $this->good->quantity }} {{ $this->good->unit }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500">Stok Tersedia</p>
            <p class="font-bold {{ $this->good->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $this->good->stock }} {{ $this->good->unit }}
            </p>
        </div>
        <div>
            <p class="text-xs text-slate-500">Sumber Dana</p>
            <p class="font-bold text-slate-900">{{ $this->good->funding_source ?? '-' }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-500">Supplier</p>
            <p class="font-bold text-slate-900">{{ $this->good->supplier->name ?? '-' }}</p>
        </div>
    </div>

    <p class="text-sm font-semibold text-slate-700 mb-2">📋 Daftar Kode Inventaris per Unit</p>
    {{ $this->table }}
</x-filament-panels::page>