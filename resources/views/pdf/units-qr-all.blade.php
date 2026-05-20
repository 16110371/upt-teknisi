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
            /* ✅ tambah padding body */
        }

        @page {
            margin: 2cm;
            /* ✅ perbesar margin halaman */
            size: A4 portrait;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
        }

        td {
            width: 25%;
            border: 1.5px solid #1e40af;
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
            color: #1e40af;
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
    </style>
</head>

<body>
    <table>
        @foreach ($unitsWithQr->chunk(4) as $row)
            <tr>
                @foreach ($row as $item)
                    <td>
                        <img src="{{ public_path('images/logo-garjo-smk.png') }}" class="logo" alt="Logo">
                        <p class="school-name">SMK Syubbanul Wathon</p>
                        <img src="data:image/svg+xml;base64,{{ $item['qrBase64'] }}" width="100" height="100"
                            alt="QR">
                        <p class="unit-code">{{ $item['unit']->code }}</p>
                        <p class="unit-info">{{ $item['unit']->infrastructure->name }}</p>
                        <p class="unit-info">{{ $item['unit']->infrastructure->location->name }}</p>
                    </td>
                @endforeach

                {{-- ✅ Isi kolom kosong kalau kurang dari 4 --}}
                @for ($i = $row->count(); $i < 4; $i++)
                    <td style="border: none;"></td>
                @endfor
            </tr>
        @endforeach
    </table>
</body>

</html>
