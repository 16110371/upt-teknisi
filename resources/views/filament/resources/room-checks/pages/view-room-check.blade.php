<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Header Info --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Lokasi</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $record->location->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Diperiksa Oleh</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $record->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tanggal</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                        {{ $record->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Item</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $record->items->count() }} item</p>
                </div>
            </div>

            {{-- Summary --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-6">
                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-green-600">
                        {{ $record->items->where('status', 'OK')->count() }}
                    </p>
                    <p class="text-xs text-green-700 dark:text-green-400 font-medium mt-1">✅ Item OK</p>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-red-600">
                        {{ $record->items->where('status', 'Bermasalah')->count() }}
                    </p>
                    <p class="text-xs text-red-700 dark:text-red-400 font-medium mt-1">⚠️ Item Bermasalah</p>
                </div>
                <div class="bg-orange-50 dark:bg-orange-900/20 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-orange-600">
                        {{ $record->items->where('status', 'Bermasalah')->sum('quantity') }}
                    </p>
                    <p class="text-xs text-orange-700 dark:text-orange-400 font-medium mt-1">🔧 Total Bermasalah</p>
                </div>
            </div>
        </div>

        {{-- Item List --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 p-6">
            <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">📋 Detail Item</h2>

            <div class="space-y-3">
                @foreach ($record->items as $item)
                <div class="rounded-xl border-2 p-4
                {{ $item->status === 'Bermasalah'
                    ? 'border-red-200 bg-red-50 dark:bg-red-900/10 dark:border-red-800'
                    : 'border-gray-100 bg-gray-50 dark:bg-gray-800 dark:border-gray-700' }}">

                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">
                                {{ $item->infrastructure->name }}
                            </p>
                            <div class="flex flex-wrap gap-2 mt-1">
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    {{ $item->infrastructure->category->name }}
                                </span>
                            </div>
                        </div>

                        {{-- Status Badge --}}
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                        {{ $item->status === 'Bermasalah'
                            ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                            : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' }}">
                            {{ $item->status === 'Bermasalah' ? '⚠️ Bermasalah' : '✅ OK' }}
                        </span>
                    </div>

                    {{-- Detail Bermasalah --}}
                    @if ($item->status === 'Bermasalah')
                    <div class="mt-3 pt-3 border-t border-red-200 dark:border-red-800 grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Jumlah Bermasalah</p>
                            <p class="text-sm font-bold text-red-600">{{ $item->quantity }} unit</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Keterangan</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $item->note ?: '-' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

    </div>
</x-filament-panels::page>