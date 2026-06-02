@extends('layouts.app')

@section('title', 'Detail Unit - ' . $unit->code)

@section('content')
    <section class="py-12 pb-24">
        <div class="container mx-auto px-6">
            <div class="max-w-2xl mx-auto space-y-6">

                {{-- Header Unit --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900">{{ $unit->code }}</h1>
                            <p class="text-slate-500 mt-1">{{ $unit->infrastructure->category->name }}</p>
                            <p class="text-slate-500 text-sm">{{ $unit->infrastructure->location->name }}</p>
                        </div>

                        {{-- Status Badge --}}
                        <span
                            class="px-3 py-1.5 rounded-full text-sm font-bold
                        {{ $unit->status === 'good'
                            ? 'bg-green-100 text-green-700'
                            : ($unit->status === 'broken'
                                ? 'bg-orange-100 text-orange-700'
                                : 'bg-red-100 text-red-700') }}">
                            {{ $unit->status === 'good' ? '✅ Baik' : ($unit->status === 'broken' ? '🔧 Rusak' : '❌ Rusak Permanen') }}
                        </span>
                    </div>

                    @if ($unit->note)
                        <p class="mt-4 text-sm text-slate-600 bg-slate-50 rounded-lg p-3">
                            {{ $unit->note }}
                        </p>
                    @endif
                </div>

                {{-- Success Message --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-300 text-green-700 p-4 rounded-xl">
                        ✅ Laporan berhasil dikirim! Tim UPT akan segera menangani.
                    </div>
                @endif

                {{-- Form Laporkan Masalah --}}
                @if ($unit->status !== 'permanent_broken')
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-lg font-bold text-slate-900 mb-4">⚠️ Laporkan Masalah</h2>

                        <form method="POST" action="{{ route('unit.report', $unit->code) }}" class="space-y-4">
                            @csrf

                            @if ($errors->any())
                                <div class="bg-red-100 border border-red-300 text-red-700 p-3 rounded-lg text-sm">
                                    @foreach ($errors->all() as $error)
                                        <p>• {{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="requester_name" value="{{ old('requester_name') }}"
                                    placeholder="Masukkan nama lengkap"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 outline-none text-sm"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">
                                    Prioritas
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="priority" value="Rendah"
                                            {{ old('priority', 'Sedang') == 'Rendah' ? 'checked' : '' }}>
                                        <span class="text-sm">🟢 Rendah</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="priority" value="Sedang"
                                            {{ old('priority', 'Sedang') == 'Sedang' ? 'checked' : '' }}>
                                        <span class="text-sm">🟡 Sedang</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="priority" value="Tinggi"
                                            {{ old('priority', 'Sedang') == 'Tinggi' ? 'checked' : '' }}>
                                        <span class="text-sm">🔴 Tinggi</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">
                                    Deskripsi Masalah <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" placeholder="Jelaskan masalah yang terjadi..." rows="4"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 outline-none text-sm resize-none"
                                    required>{{ old('description') }}</textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 text-white px-6 py-3.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition">
                                📤 Kirim Laporan
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
                        <p class="text-red-600 font-semibold">❌ Unit ini sudah rusak permanen</p>
                        <p class="text-red-500 text-sm mt-1">Tidak dapat menerima laporan baru</p>
                    </div>
                @endif

                {{-- Riwayat Masalah --}}
                @if ($unit->logs->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-lg font-bold text-slate-900 mb-4">📋 Riwayat Masalah</h2>

                        <div class="space-y-3">
                            @foreach ($unit->logs as $log)
                                <div class="border border-slate-100 rounded-xl p-4 bg-slate-50">
                                    <div class="flex items-center justify-between gap-4">
                                        <span
                                            class="px-2 py-0.5 rounded-full text-xs font-bold
                                {{ $log->type === 'rusak'
                                    ? 'bg-red-100 text-red-700'
                                    : ($log->type === 'selesai'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-gray-100 text-gray-700') }}">
                                            {{ match ($log->type) {
                                                'rusak' => '🔧 Rusak',
                                                'selesai' => '✅ Selesai',
                                                'permanent' => '❌ Permanen',
                                                default => $log->type,
                                            } }}
                                        </span>
                                        <span class="text-xs text-slate-400">
                                            {{ $log->created_at->translatedFormat('d M Y, H:i') }}
                                        </span>
                                    </div>
                                    @if ($log->note)
                                        <p class="text-sm text-slate-600 mt-2">{{ $log->note }}</p>
                                    @endif
                                    @if ($log->request)
                                        <p class="text-xs text-slate-400 mt-1">
                                            Oleh: {{ $log->request->requester_name }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>
@endsection
