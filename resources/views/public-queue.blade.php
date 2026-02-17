<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Antrian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-indigo-100 to-gray-50 min-h-screen p-6">

<div class="max-w-5xl mx-auto bg-white rounded-xl shadow-lg p-6">

    <h1 class="text-3xl font-bold text-blue-700 mb-6 text-center">
        📋 Daftar Antrian Permintaan
    </h1>

    <div class="mb-4 text-center">
        <a href="{{ url('/permintaan') }}"
           class="text-indigo-600 hover:underline">
            ← Kembali ke Form
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border rounded-lg overflow-hidden">
            <thead class="bg-blue-700 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-left">Lokasi</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($requests as $i => $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $i+1 }}</td>
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($r->request_date)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-4 py-3">{{ $r->requester_name }}</td>
                        <td class="px-4 py-3">{{ $r->category->name }}</td>
                        <td class="px-4 py-3">{{ $r->location->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if($r->status=='Pending') bg-yellow-100 text-yellow-700
                                @elseif($r->status=='Proses') bg-blue-100 text-blue-700
                                @endif">
                                {{ $r->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">
                            Tidak ada antrian aktif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
<!-- Footer -->
    <footer class="mt-10 text-center text-gray-600 text-sm">
        <p>Powered by <span class="font-semibold text-blue-600">UPT</span> © {{ date('Y') }}</p>
    </footer>
</body>
</html>
