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
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        @page {
            margin: 2cm;
            size: A4 portrait;
        }

        .card {
            width: 220px;
            border: 2px solid #166534;
            border-radius: 12px;
            padding: 20px 16px;
            text-align: center;
            margin: 0 auto;
        }

        .logo {
            width: 55px;
            height: 55px;
            margin: 0 auto 8px;
        }

        .school-name {
            font-size: 10px;
            color: #166534;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .divider {
            border-top: 1px solid #e5e7eb;
            margin: 10px 0;
        }

        .unit-code {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
            margin-top: 12px;
            letter-spacing: 1px;
        }

        .unit-info {
            font-size: 9px;
            color: #6b7280;
            margin-top: 4px;
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
    <div class="card">
        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo SMK">
        <p class="school-name">SMK Syubbanul Wathon</p>

        <div class="divider"></div>

        <img src="data:image/svg+xml;base64,{{ $qrBase64 }}"
            width="180" height="180" alt="QR Code">

        <div class="divider"></div>

        <p class="unit-code">{{ $unit->code }}</p>
        <p class="unit-info">{{ $unit->good->goodsType->name ?? $unit->good->name }}</p>
        <p class="unit-info">{{ $unit->good->brand ?? '-' }}</p>
        <p class="unit-info">{{ $unit->location->name }}</p>
    </div>
</body>

</html>