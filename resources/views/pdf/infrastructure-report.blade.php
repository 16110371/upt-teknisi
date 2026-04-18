<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Infrastruktur</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #1e293b;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 12px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }

        .header p {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
        }

        .info {
            margin-bottom: 16px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            padding: 3px 6px;
            font-size: 11px;
        }

        .info td:first-child {
            font-weight: bold;
            width: 140px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table.data th {
            background-color: #1e40af;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
        }

        table.data td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }

        table.data tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .badge {
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .summary {
            margin-top: 20px;
            display: flex;
            gap: 12px;
        }

        .summary-box {
            flex: 1;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
        }

        .summary-box .number {
            font-size: 22px;
            font-weight: bold;
        }

        .summary-box .label {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        .footer {
            margin-top: 24px;
            text-align: right;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="header">
        <h1>Laporan Infrastruktur</h1>
        <p>SMK Syubbanul Wathon - Unit Pelayanan Terpadu</p>
    </div>

    {{-- Info Laporan --}}
    <div class="info">
        <table>
            <tr>
                <td>Lokasi</td>
                <td>: {{ $location ?? 'Semua Lokasi' }}</td>
            </tr>
            <tr>
                <td>Tanggal Cetak</td>
                <td>: {{ now()->format('d M Y, H:i') }}</td>
            </tr>
            <tr>
                <td>Total Item</td>
                <td>: {{ $infrastructures->count() }} item</td>
            </tr>
        </table>
    </div>

    {{-- Summary --}}
    <table style="width:100%; margin-bottom: 16px;">
        <tr>
            <td style="width:25%; padding: 8px; text-align:center; border: 1px solid #e2e8f0; border-radius:4px;">
                <div style="font-size:20px; font-weight:bold; color:#1e40af;">{{ $infrastructures->sum('total') }}</div>
                <div style="font-size:10px; color:#64748b;">Total Keseluruhan</div>
            </td>
            <td style="width:5%;"></td>
            <td style="width:25%; padding: 8px; text-align:center; border: 1px solid #e2e8f0;">
                <div style="font-size:20px; font-weight:bold; color:#166534;">{{ $infrastructures->sum('good') }}</div>
                <div style="font-size:10px; color:#64748b;">Kondisi Baik</div>
            </td>
            <td style="width:5%;"></td>
            <td style="width:25%; padding: 8px; text-align:center; border: 1px solid #e2e8f0;">
                <div style="font-size:20px; font-weight:bold; color:#854d0e;">{{ $infrastructures->sum('broken') }}
                </div>
                <div style="font-size:10px; color:#64748b;">Sedang Rusak</div>
            </td>
            <td style="width:5%;"></td>
            <td style="width:25%; padding: 8px; text-align:center; border: 1px solid #e2e8f0;">
                <div style="font-size:20px; font-weight:bold; color:#991b1b;">
                    {{ $infrastructures->sum('permanent_broken') }}</div>
                <div style="font-size:10px; color:#64748b;">Rusak Permanen</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Data --}}
    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Lokasi</th>
                <th>Kategori</th>
                <th>Nama Item</th>
                <th>Total</th>
                <th>Baik</th>
                <th>Rusak</th>
                <th>Permanen</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($infrastructures as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->location->name }}</td>
                    <td>{{ $item->category->name }}</td>
                    <td>{{ $item->name }}</td>
                    <td style="text-align:center;">{{ $item->total }}</td>
                    <td style="text-align:center;">
                        <span class="badge badge-success">{{ $item->good }}</span>
                    </td>
                    <td style="text-align:center;">
                        <span
                            class="badge {{ $item->broken > 0 ? 'badge-warning' : 'badge-success' }}">{{ $item->broken }}</span>
                    </td>
                    <td style="text-align:center;">
                        <span
                            class="badge {{ $item->permanent_broken > 0 ? 'badge-danger' : 'badge-success' }}">{{ $item->permanent_broken }}</span>
                    </td>
                    <td>{{ $item->note ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        Dicetak pada {{ now()->format('d M Y, H:i') }} &bull; UPT SMK Syubbanul Wathon
    </div>

</body>

</html>
