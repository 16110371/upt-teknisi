<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Permintaan</title>
    <style>
        /* 1. Reset Total */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* 2. Pengaturan Kertas */
        @page {
            /* Pastikan margin kiri dan kanan SAMA agar seimbang */
            margin: 1.5cm 1.5cm !important;
            size: A4 portrait;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.4;
            /* Hapus padding body agar tidak mendorong tabel ke kanan */
            background-color: white;
        }

        /* 3. Wrapper untuk memastikan konten di tengah */
        .wrapper {
            width: 100%;
            margin: 0 auto;
        }

        /* Header Table */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 12px;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
            /* Memberi jarak antara konten (logo/teks) dengan garis biru di bawah */
            padding-bottom: 15px;
        }

        .header-logo {
            max-height: 60px;
            max-width: 80px;
            /* Batas lebar agar logo portrait tidak melebar */
            width: auto;
            height: auto;
            vertical-align: middle;
        }

        .logo-left {
            max-height: 55px;
        }

        .header-text {
            text-align: center;
            /* Teks otomatis ke tengah */
        }

        .header-text h1 {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
        }

        .header-text p {
            font-size: 10px;
            color: #64748b;
            margin: 2px 0;
        }

        /* Summary Box */
        .summary {
            margin-bottom: 20px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 12px;
        }

        .summary table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Data Table */
        table.data {
            width: 100%;
            border-collapse: collapse;
            /* table-layout: fixed sangat krusial agar tidak meluber ke kanan */
            table-layout: fixed;
            word-wrap: break-word;
        }

        table.data th {
            background-color: #1e40af;
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-size: 10px;
            /* Sedikit diperkecil agar tidak sesak */
            border: 0.5pt solid #1e40af;
        }

        table.data td {
            padding: 6px 4px;
            border: 0.5pt solid #e2e8f0;
            font-size: 9px;
            vertical-align: top;
        }

        table.data tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Badge Style */
        .badge {
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-align: center;
        }

        .badge-warning {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-gray {
            background: #f1f5f9;
            color: #475569;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Tanda Tangan */
        .footer-sign {
            margin-top: 30px;
            width: 100%;
        }

        /* Footer Cetak */
        .footer {
            position: fixed;
            bottom: -10px;
            /* Tarik sedikit ke bawah */
            width: 100%;
            text-align: right;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        {{-- Header --}}
        <table class="header-table">
            <tr>
                <!-- Logo Kiri -->
                <td width="15%" style="text-align: left;">
                    <img src="{{ public_path('images/logo.png') }}" class="header-logo" alt="Logo Kiri">
                </td>

                <!-- Teks Tengah -->
                <td width="70%" class="header-text">
                    <h1>Laporan Permintaan Perbaikan</h1>
                    <p>SMK Syubbanul Wathon - Unit Pelayanan Teknis</p>
                    <p style="font-style: italic;">Jl. Kyai Abdan Tepo No.03, Gemuh, Dlimas, Kec. Tegalrejo, Kabupaten
                        Magelang, Jawa Tengah 56192
                    </p>
                </td>

                <!-- Logo Kanan -->
                <td width="15%" style="text-align: right;">
                    <img src="{{ public_path('images/logo-garjo-smk.png') }}" class="header-logo" alt="Logo Kanan">
                </td>
            </tr>
        </table>

        {{-- Summary --}}
        <div class="summary">
            <table>
                <tr>
                    <td width="15%"><strong>Tanggal Cetak</strong></td>
                    <td width="35%">: {{ $printed_at->locale('id')->translatedFormat('d F Y, H:i') }}</td>
                    <td width="15%"><strong>Total Data</strong></td>
                    <td width="35%">: {{ $reports->count() }} permintaan</td>
                </tr>
                <tr>
                    <td><strong>Pending</strong></td>
                    <td>: {{ $reports->where('status', 'Pending')->count() }}</td>
                    <td><strong>Selesai</strong></td>
                    <td>: {{ $reports->where('status', 'Selesai')->count() }}</td>
                </tr>
                <tr>
                    <td><strong>Dikerjakan</strong></td>
                    <td>: {{ $reports->where('status', 'Dikerjakan')->count() }}</td>
                    <td><strong>Tidak Diperbaiki</strong></td>
                    <td>: {{ $reports->where('status', 'Tidak Diperbaiki')->count() }}</td>
                </tr>
            </table>
        </div>

        {{-- Tabel Utama --}}
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="14%">Tanggal</th>
                    <th width="12%">Peminta</th>
                    <th width="10%">Lokasi</th>
                    <th width="10%">Kategori</th>
                    <th width="12%">Item</th>
                    <th width="4%">Rsk</th>
                    <th width="4%">Fix</th>
                    <th width="12%">Status</th>
                    <th width="16%">Teknisi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $i => $r)
                    <tr>
                        <td style="text-align:center;">{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->request_date)->locale('id')->translatedFormat('d F Y') }}</td>
                        <td>{{ $r->requester_name }}</td>
                        <td>{{ $r->location->name }}</td>
                        <td>{{ $r->category->name }}</td>
                        <td>{{ $r->infrastructure->name ?? '-' }}</td>
                        <td style="text-align:center;">{{ $r->damaged_quantity ?? 0 }}</td>
                        <td style="text-align:center;">{{ $r->fixed_quantity ?? 0 }}</td>
                        <td style="text-align:center;">
                            @php
                                $badgeClass = match ($r->status) {
                                    'Pending' => 'badge-warning',
                                    'Dikerjakan' => 'badge-info',
                                    'Menunggu Part' => 'badge-gray',
                                    'Selesai' => 'badge-success',
                                    'Tidak Diperbaiki' => 'badge-danger',
                                    default => 'badge-gray',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $r->status }}</span>
                        </td>
                        <td>{{ $r->technicians->pluck('name')->implode(', ') ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Tanda Tangan --}}
        <div class="footer-sign">
            <table width="100%">
                <tr>
                    <td width="65%"></td>
                    <td width="35%" style="text-align: center;">
                        <p>Magelang, {{ $printed_at->locale('id')->translatedFormat('d F Y') }}</p>
                        <p style="margin-top: 5px;"><strong>Kepala UPT</strong></p>
                        <br><br><br><br>
                        <p><u><strong>Ahmad Kuswanto, Amd</strong></u></p>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Footer otomatis di setiap halaman --}}
    <div class="footer">
        Dicetak pada {{ $printed_at->locale('id')->translatedFormat('d F Y, H:i') }} &bull; UPT SMK Syubbanul Wathon
    </div>

</body>

</html>
