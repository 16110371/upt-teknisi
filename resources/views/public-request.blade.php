@extends('layouts.app')

@section('title', 'UPT - SMK Syubbanul Wathon')

@section('content')

<!-- Main Form Section -->
<section class="py-12 pb-24">
    <div class="container mx-auto px-6">
        <div class="max-w-3xl mx-auto">
            @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 p-4 rounded-lg mb-6">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <!-- @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
            @endif -->
            <div x-data="{ loading: false, success: {{ session()->has('success') ? 'true' : 'false' }} }" x-init="if(success){ setTimeout(() => { window.location.href = '/antrian'; }, 2000)}">
                <form method="POST" action="{{ route('public-request.store') }}" enctype="multipart/form-data" @submit="loading = true" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 lg:p-12 space-y-8">
                    @csrf
                    <div x-show="loading"
                        x-cloak
                        x-transition
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

                        <div class="bg-white rounded-xl shadow-xl px-8 py-6 flex flex-col items-center gap-4">

                            <!-- Spinner -->
                            <svg class="animate-spin h-8 w-8 text-blue-600" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
                            </svg>

                            <!-- Text -->
                            <p class="text-sm font-semibold text-slate-700">
                                Mengirim ...
                            </p>

                        </div>
                    </div>
                    <!-- Section 1: Data Pelapor -->
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-200">
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold mr-3">1</span>
                            Data Pelapor
                        </h2>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" placeholder="Masukkan nama lengkap Anda" name="requester_name"
                                    value="{{ old('requester_name') }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition"
                                    required>
                                @error('requester_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Informasi Perangkat -->
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-200">
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold mr-3">2</span>
                            Informasi Perangkat/Fasilitas
                        </h2>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">
                                    Lokasi/Ruangan <span class="text-red-500">*</span>
                                </label>
                                <select name="location_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition"
                                    required>
                                    <option value="">-- Pilih Lokasi --</option>
                                    @foreach ($locations as $loc)
                                    <option value="{{ $loc->id }}"
                                        {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                                        {{ $loc->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">
                                    Tipe Perangkat/Fasilitas <span class="text-red-500">*</span>
                                </label>
                                <select name="category_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition"
                                    required>
                                    <option value="">-- Pilih Tipe --</option>
                                    @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Section 2b: Infrastruktur (dynamic) -->
                    <div id="infrastructure-section" style="display:none;">
                        <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-200">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold mr-3">2b</span>
                            Detail Item
                        </h2>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">
                                    Item yang Rusak <span class="text-slate-500">(Opsional)</span>
                                </label>
                                <select name="infrastructure_id" id="infrastructure_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition">
                                    <option value="">-- Pilih Item --</option>
                                </select>
                            </div>

                            <div id="quantity-section" style="display:none;">
                                <label class="block text-sm font-semibold text-slate-900 mb-2">
                                    Jumlah yang Rusak <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="damaged_quantity" id="damaged_quantity"
                                    value="{{ old('damaged_quantity', 1) }}"
                                    min="1"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition">
                            </div>
                        </div>
                    </div>
                    <!-- Section 3: Detail Kerusakan -->
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-200">
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold mr-3">3</span>
                            Detail Kerusakan
                        </h2>

                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">
                                Tingkat Prioritas<span class="text-slate-500">(Opsional)</span>
                            </label>
                            <div class="flex flex-wrap gap-4 mb-6">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="priority" value="Rendah"
                                        {{ old('priority', 'Rendah') == 'Rendah' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-slate-600 font-medium">
                                        🟢 Rendah (bisa ditunda)
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="priority" value="Sedang"
                                        {{ old('priority', 'Rendah') == 'Sedang' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-slate-600 font-medium">
                                        🟡 Sedang (butuh perbaikan)
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="priority" value="Tinggi"
                                        {{ old('priority', 'Rendah') == 'Tinggi' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-slate-600 font-medium">
                                        🔴 Tinggi (urgent)
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-slate-900 mb-2">
                                Deskripsi Masalah <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description"
                                placeholder="Jelaskan detail masalah dengan seksama:&#10;- Apa yang terjadi&#10;- Gejala yang muncul&#10;- Kapan pertama kali terjadi&#10;- Apakah sudah ada solusi yang dicoba"
                                rows="6"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition resize-none"
                                required>{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Section 4: Lampiran -->
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-200">
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold mr-3">4</span>
                            Lampiran & Keterangan Tambahan
                        </h2>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">
                                Foto/Screenshot Masalah
                                <span class="text-slate-500">(Opsional)</span>
                            </label>

                            <div id="drop-zone"
                                class="relative border-2 border-dashed border-slate-300 rounded-lg h-56 flex items-center justify-center text-center hover:border-blue-600 hover:bg-blue-50 transition cursor-pointer overflow-hidden">

                                <input name="photo" type="file" accept="image/*" class="hidden" id="file-input" />

                                <!-- Default Upload Content -->
                                <div id="upload-content">
                                    <p class="text-sm text-slate-600 font-medium">
                                        Klik untuk upload atau drag & drop file
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        JPG / PNG max 5MB
                                    </p>
                                </div>

                                <!-- Image Preview -->
                                <img id="image-preview" class="hidden w-full h-full object-contain bg-slate-100" />

                                <!-- Delete Button -->
                                <button type="button" id="remove-image"
                                    class="hidden absolute top-2 right-2
                       bg-white/80 backdrop-blur
                       hover:bg-red-500 hover:text-white
                       text-slate-700
                       w-8 h-8 rounded-full shadow
                       flex items-center justify-center
                       transition">
                                    ✕
                                </button>

                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <button :disabled="loading" type="submit"
                            class="flex-1 bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Kirim Laporan
                        </button>
                        <button type="reset" id="reset-btn"
                            class="flex-1 bg-slate-200 text-slate-900 px-8 py-4 rounded-lg font-semibold hover:bg-slate-300 transition">
                            Reset Form
                        </button>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                        <p class="text-sm text-slate-700">
                            <span class="font-semibold text-blue-600">ℹ️ Informasi:</span>
                            Laporan Anda akan diverifikasi dan diproses oleh tim UPT.
                        </p>
                    </div>
                </form>
                <div x-show="success"
                    x-cloak
                    x-transition.opacity.duration.300ms
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

                    <div class="bg-white rounded-xl shadow-xl px-8 py-6 flex flex-col items-center gap-4 text-center">

                        <div class="w-16 h-16 flex items-center justify-center rounded-full bg-green-100">
                            <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>

                        <h2 class="text-lg font-bold text-slate-900">
                            Berhasil Terkirim!
                        </h2>

                        <p class="text-sm text-slate-600">
                            Terima kasih, laporan Anda akan segera kami proses. 🎉
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    const fileInput = document.getElementById('file-input');
    const dropZone = document.getElementById('drop-zone');
    const preview = document.getElementById('image-preview');
    const uploadContent = document.getElementById('upload-content');
    const removeBtn = document.getElementById('remove-image');
    const resetBtn = document.getElementById('reset-btn');

    // Klik dropzone → buka file
    dropZone.addEventListener('click', (e) => {
        if (e.target.id !== 'remove-image') {
            fileInput.click();
        }
    });

    // Preview gambar
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar!');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            uploadContent.classList.add('hidden');
            removeBtn.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    // Remove image
    removeBtn.addEventListener('click', function(e) {
        e.stopPropagation();

        preview.src = '';
        preview.classList.add('hidden');
        uploadContent.classList.remove('hidden');
        removeBtn.classList.add('hidden');
        fileInput.value = '';
    });

    // Reset form handler
    resetBtn.addEventListener('click', function() {
        // Reset preview foto
        preview.src = '';
        preview.classList.add('hidden');
        uploadContent.classList.remove('hidden');
        removeBtn.classList.add('hidden');
        fileInput.value = '';
    });

    // Drag & Drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-600', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-blue-600', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-600', 'bg-blue-50');

        const file = e.dataTransfer.files[0];
        fileInput.files = e.dataTransfer.files;

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            uploadContent.classList.add('hidden');
            removeBtn.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    // ✅ Dynamic load infrastruktur berdasarkan lokasi & kategori
    const locationSelect = document.querySelector('select[name="location_id"]');
    const categorySelect = document.querySelector('select[name="category_id"]');
    const infraSection = document.getElementById('infrastructure-section');
    const infraSelect = document.getElementById('infrastructure_id');
    const quantitySection = document.getElementById('quantity-section');

    async function loadInfrastructures() {
        const locationId = locationSelect.value;
        const categoryId = categorySelect.value;

        // Reset
        infraSelect.innerHTML = '<option value="">-- Pilih Item --</option>';
        quantitySection.style.display = 'none';

        if (!locationId || !categoryId) {
            infraSection.style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`/api/infrastructures?location_id=${locationId}&category_id=${categoryId}`);
            const data = await response.json();

            if (data.length > 0) {
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.name} (Baik: ${item.good}, Rusak: ${item.broken})`;
                    infraSelect.appendChild(option);
                });
                infraSection.style.display = 'block';
            } else {
                infraSection.style.display = 'none';
            }
        } catch (err) {
            console.error('Error load infrastruktur:', err);
            infraSection.style.display = 'none';
        }
    }

    // Tampilkan quantity saat item dipilih
    infraSelect.addEventListener('change', function() {
        quantitySection.style.display = this.value ? 'block' : 'none';
    });

    locationSelect.addEventListener('change', loadInfrastructures);
    categorySelect.addEventListener('change', loadInfrastructures);
</script>
@endpush