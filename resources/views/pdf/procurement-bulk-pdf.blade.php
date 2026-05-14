<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
        }

        .kop-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-table td {
            border: none !important;
        }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.main-table th,
        table.main-table td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        .footer-section {
            width: 100%;
            margin-top: 30px;
        }

        .footer-section td {
            border: none !important;
            text-align: center;
            width: 33%;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .spacer {
            height: 50px;
        }
    </style>
</head>

<body>
    <table class="kop-table">
        <tr>
            <td style="width: 80px;"><img src="{{ public_path('images/logo-garjo-smk.png') }}" width="70"></td>
            <td style="text-align: center;">
                <h3 style="margin:0">YAYASAN SYUBBANUL WATHON</h3>
                <h3 style="margin:0">SMK SYUBBANUL WATHON TEGALREJO</h3>
                <p style="font-size: 8pt">Jalan K. Abdan 03 Tepo Dlimas Tegalrejo Magelang 56192</p>
            </td>
            <td style="width: 80px;"></td>
        </tr>
    </table>

    <h4 style="text-align: center; text-transform: uppercase;">REKAPITULASI RENCANA PENGAJUAN SARPRAS IT</h4>

    <table class="main-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
                <th>Tempat</th>
                <th>Pemohon</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach ($records as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td align="left">{{ $item->item_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td align="right">Rp {{ number_format($item->estimated_price, 0, ',', '.') }}</td>
                    <td align="right">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                    <td>{{ $item->location?->name }}</td>
                    <td>{{ $item->requested_by }}</td>
                </tr>
                @php $grandTotal += $item->total_price; @endphp
            @endforeach
            <tr>
                <td colspan="4"><strong>GRAND TOTAL</strong></td>
                <td align="right"><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <table class="footer-section">
        <tr>
            <td colspan="2"></td>
            <td>Tegalrejo, {{ now()->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Mengetahui,<br>Waka Sarpras</td>
            <td>Ka. UPT</td>
            <td>Menyetujui,<br>Kepala Sekolah</td>
        </tr>
        <tr>
            <td class="spacer"></td>
            <td class="spacer"></td>
            <td class="spacer"></td>
        </tr>
        <tr>
            <td><span class="signature-name">( Nauwaf Mu'arif, S.Kom )</span></td>
            <td><span class="signature-name">( Ahmad Kuswanto, A.Md )</span></td>
            <td><span class="signature-name">( Eko Marwati Rahayuningsih, S.Pd.Si )</span></td>
        </tr>
    </table>
</body>

</html>
