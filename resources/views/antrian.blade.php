@extends('layouts.app')

@section('title', 'UPT - SMK Syubbanul Wathon')

@section('content')

<section class="py-16">
    <div class="container mx-auto px-6">

        <h1 class="text-3xl font-bold text-slate-900 mb-2">
            Daftar Antrian Perbaikan
        </h1>
        <p class="text-slate-500 mb-8 text-sm">
            Menampilkan permintaan yang sedang dalam proses penanganan
        </p>

        @if ($requests->isEmpty())
        <div class="text-center py-20">
            <div class="text-5xl mb-4">🎉</div>
            <p class="text-slate-500 font-medium">Tidak ada antrian saat ini</p>
            <p class="text-slate-400 text-sm mt-1">Semua permintaan sudah ditangani</p>
        </div>
        @else
        <div class="overflow-x-auto bg-white rounded-2xl shadow border border-slate-200">
            <div class="min-w-[900px]">
                <table class="w-full text-sm">

                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-slate-600">No</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-600">Pelapor</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-600">Lokasi</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-600">Perangkat</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-600">Item</th>
                            <!-- <th class="px-6 py-4 text-left font-semibold text-slate-600">Teknisi</th> -->
                            <th class="px-6 py-4 text-left font-semibold text-slate-600">Prioritas</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-600">Status</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-600">Tanggal</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @foreach ($requests as $item)
                        <tr class="hover:bg-slate-50 transition">

                            {{-- Nomor --}}
                            <td class="px-6 py-4 font-bold text-blue-600">
                                #{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}
                            </td>

                            {{-- Pelapor --}}
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">
                                    {{ $item->requester_name }}
                                </div>
                                @if ($item->requester_contact)
                                <div class="text-xs text-slate-500">
                                    {{ $item->requester_contact }}
                                </div>
                                @endif
                            </td>

                            {{-- Lokasi --}}
                            <td class="px-6 py-4 text-slate-700">
                                {{ $item->location->name }}
                            </td>

                            {{-- Perangkat --}}
                            <td class="px-6 py-4 text-slate-700">
                                {{ $item->category->name }}
                            </td>

                            {{-- Item Infrastruktur --}}
                            <td class="px-6 py-4 text-slate-700">
                                @if ($item->infrastructure)
                                <div>{{ $item->infrastructure->name }}</div>
                                <div class="text-xs text-slate-400">
                                    Jml: {{ $item->damaged_quantity }}
                                </div>
                                @else
                                <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- {{-- Teknisi --}}
                            <td class="px-6 py-4 text-slate-700">
                                {{ $item->technician?->name ?? '-' }}
                            </td> -->

                            {{-- Prioritas --}}
                            <td class="px-6 py-4">
                                @if ($item->priority == 'Tinggi')
                                <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700 font-semibold">
                                    🔴 Tinggi
                                </span>
                                @elseif ($item->priority == 'Sedang')
                                <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-semibold">
                                    🟡 Sedang
                                </span>
                                @else
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 font-semibold">
                                    🟢 Rendah
                                </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                @if ($item->status == 'Pending')
                                <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-semibold">
                                    ⏳ Menunggu
                                </span>
                                @elseif ($item->status == 'Dikerjakan')
                                <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-semibold">
                                    ⚙️ Dikerjakan
                                </span>
                                @elseif ($item->status == 'Menunggu Part')
                                <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700 font-semibold">
                                    🔧 Menunggu Part
                                </span>
                                @endif
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-6 py-4 text-slate-500">
                                {{ \Carbon\Carbon::parse($item->request_date)->format('d M Y') }}
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
        @endif

    </div>
</section>

@endsection