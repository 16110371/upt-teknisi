<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        @page {
            margin: 1.5cm;
            size: A4 portrait;
        }

        .header {
            text-align: center;
            margin-bottom: 14px;
            border-bottom: 2px solid #166534;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            color: #166534;
        }

        .header p {
            font-size: 10px;
            color: #6b7280;
            margin-top: 3px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
        }

        td {
            width: 25%;
            border: 1.5px solid #166534;
            border-radius: 8px;
            padding: 6px;
            text-align: center;
            vertical-align: top;
        }

        .logo {
            width: 25px;
            height: 25px;
            margin: 0 auto 3px;
        }

        .school-name {
            font-size: 6px;
            color: #166534;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .unit-code {
            font-size: 8px;
            font-weight: bold;
            color: #111827;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        .unit-info {
            font-size: 6px;
            color: #6b7280;
            margin-top: 2px;
        }

        .border-bos {
            border-color: #2563eb;
        }

        /* Biru - BOS */
        .border-bosda {
            border-color: #16a34a;
        }

        /* Hijau - BOSDA */
        .border-sekolah {
            border-color: #dc2626;
        }

        /* Merah - Sekolah */
        .border-bantuan {
            border-color: #d97706;
        }

        /* Kuning - Bantuan */
        .border-default {
            border-color: #6b7280;
        }

        /* Abu - Tidak ada */
    </style>
</head>

<body>

    <div class="header">
        <h1>Cetak Kode Inventaris</h1>
        <p>{{ $unitsWithQr->count() }} unit &bull; {{ now()->translatedFormat('d F Y') }}</p>
    </div>
    <table>
        @foreach ($unitsWithQr->chunk(4) as $row)
        <tr>
            @foreach ($row as $item)
            {{-- ✅ Pindah ke dalam loop --}}
            @php
            $borderClass = match($item['unit']->good->funding_source ?? null) {
            'BOS' => 'border-bos',
            'BOSDA' => 'border-bosda',
            'Sekolah' => 'border-sekolah',
            'Bantuan' => 'border-bantuan',
            default => 'border-default',
            };
            $textColor = match($item['unit']->good->funding_source ?? null) {
            'BOS' => '#2563eb',
            'BOSDA' => '#16a34a',
            'Sekolah' => '#dc2626',
            'Bantuan' => '#d97706',
            default => '#6b7280',
            };
            @endphp
            <td class="{{ $borderClass }}">
                <img src="{{ public_path('images/logo-garjo-smk.png') }}" class="logo" alt="Logo">
                <p class="school-name" style="color: {{ $textColor }};">
                    SMK Syubbanul Wathon
                </p>
                <img src="data:image/svg+xml;base64,{{ $item['qrBase64'] }}"
                    width="100" height="100" alt="QR">
                <p class="unit-code">{{ $item['unit']->code }}</p>
                <p class="unit-info">{{ $item['unit']->good->goodsType->name ?? $item['unit']->good->name }}</p>
                <p class="unit-info">{{ $item['unit']->location->name }}</p>
            </td>
            @endforeach

            @for ($i = $row->count(); $i < 4; $i++)
                <td style="border:none;">
                </td>
                @endfor
        </tr>
        @endforeach
    </table>

</body>

</html>