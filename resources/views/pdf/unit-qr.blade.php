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
            margin: 1cm;
            size: A4 portrait;
        }

        .card {
            width: 200px;
            border: 2px solid #1e40af;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            margin: 20px auto;
        }

        .logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 8px;
        }

        .school-name {
            font-size: 10px;
            color: #1e40af;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .qr-code {
            margin: 8px auto;
        }

        .unit-code {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            margin-top: 10px;
            letter-spacing: 1px;
        }

        .unit-info {
            font-size: 9px;
            color: #6b7280;
            margin-top: 4px;
        }
    </style>
</head>

<body>
    <div class="card">
        {{-- Logo --}}
        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo SMK">

        {{-- Nama Sekolah --}}
        <p class="school-name">SMK Syubbanul Wathon</p>

        {{-- QR Code --}}
        <div class="qr-code">
            <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" width="200" height="200" alt="QR Code">
        </div>

        {{-- Kode Unit --}}
        <p class="unit-code">{{ $unit->code }}</p>

        {{-- Info Unit --}}
        <p class="unit-info">{{ $unit->infrastructure->name }}</p>
        <p class="unit-info">{{ $unit->infrastructure->location->name }}</p>
    </div>
</body>

</html>
