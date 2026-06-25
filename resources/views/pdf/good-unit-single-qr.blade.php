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
            padding: 10px;
        }

        /* 📄 Menggunakan margin 2cm sesuai yang Anda inginkan */
        @page {
            margin: 2cm;
            size: A4 portrait;
        }

        /* 📐 Menggunakan layout table & td yang sama persis dengan versi bulk */
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

        /* 🎨 Variasi Warna Border */
        .border-bos {
            border-color: #2563eb;
        }

        .border-bosda {
            border-color: #16a34a;
        }

        .border-sekolah {
            border-color: #dc2626;
        }

        .border-bantuan {
            border-color: #d97706;
        }

        .border-default {
            border-color: #6b7280;
        }
    </style>
</head>

<body>
    @php
    $fundingSource = $unit->good->funding_source ?? null;

    $borderClass = match($fundingSource) {
    'BOS' => 'border-bos',
    'BOSDA' => 'border-bosda',
    'Sekolah' => 'border-sekolah',
    'Bantuan' => 'border-bantuan',
    default => 'border-default',
    };

    $textColor = match($fundingSource) {
    'BOS' => '#2563eb',
    'BOSDA' => '#16a34a',
    'Sekolah' => '#dc2626',
    'Bantuan' => '#d97706',
    default => '#6b7280',
    };
    @endphp

    <table>
        <tr>
            <td class="{{ $borderClass }}">
                <img src="{{ public_path('images/logo-garjo-smk.png') }}" class="logo" alt="Logo">
                <p class="school-name" style="color: {{ $textColor }};">
                    SMK Syubbanul Wathon
                </p>

                <img src="data:image/svg+xml;base64,{{ $qrBase64 }}"
                    width="100" height="100" alt="QR Code">

                <p class="unit-code">{{ $unit->code }}</p>
                <p class="unit-info">{{ $unit->good->goodsType->name ?? $unit->good->name }}</p>
                <p class="unit-info">{{ $unit->location->name }}</p>
            </td>

            <td style="border: none;"></td>
            <td style="border: none;"></td>
            <td style="border: none;"></td>
        </tr>
    </table>
</body>

</html>