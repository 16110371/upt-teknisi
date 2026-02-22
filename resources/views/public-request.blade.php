<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Permintaan Layanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #e0e7ff, #f8fafc);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2rem 1rem;
        }

        .card {
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border-radius: 1rem;
            padding: 2rem;
            transition: 0.3s ease;
            max-width: 640px;
            width: 100%;
            margin: auto;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        input,
        select,
        textarea {
            transition: 0.2s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }
    </style>
</head>

<body>

    <main class="flex-grow flex items-center justify-center">
        <div class="card">
            <h1 class="text-3xl font-extrabold text-center text-blue-700 mb-6">Form Permintaan Layanan</h1>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('public-request.store') }}" enctype="multipart/form-data"
                class="space-y-5">
                @csrf


                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700">Nama Peminta <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="requester_name"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700" required
                        placeholder="Masukkan nama Anda">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700">
                        Tingkat Prioritas <span class="text-red-500">*</span>
                    </label>

                    <select name="priority" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-white">
                        <option value="">Pilih Prioritas</option>
                        <option value="Rendah" selected>🟢 Rendah</option>
                        <option value="Sedang">🔵 Sedang</option>
                        <option value="Tinggi">🟡 Tinggi</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Kategori <span
                                class="text-red-500">*</span></label>
                        <select name="category_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-white">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Lokasi <span
                                class="text-red-500">*</span></label>
                        <select name="location_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 bg-white">
                            <option value="">Pilih Lokasi</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700">Deskripsi Permintaan <span
                            class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700"
                        placeholder="Jelaskan permasalahan atau kebutuhan..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700">Foto (Opsional)</label>
                    <input type="file" name="photo" accept="image/*"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700">
                </div>

                <div class="pt-4 flex justify-center">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md hover:shadow-lg transition-all">
                        🚀 Kirim Permintaan
                    </button>
                </div>

            </form>
            <div class="mb-4 mt-6 text-center">
                <a href="{{ route('public.queue') }}"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2 rounded-lg shadow-md hover:shadow-lg transition">
                    📋 Lihat Antrian Saat Ini
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-10 text-center text-gray-600 text-sm">
        <p>Powered by <span class="font-semibold text-blue-600">UPT</span> © {{ date('Y') }}</p>
    </footer>

</body>

</html>
