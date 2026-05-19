<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            margin: 1.5cm;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.3;
        }

        /* Kop Surat Tanpa Garis Kotak, hanya Garis Ganda di Bawah */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border: none !important;
            border-bottom: 4px double #000 !important;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-table td {
            border: none !important;
            padding: 0;
            vertical-align: middle;
        }

        .title-box {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.5;
        }

        .identity-table {
            width: 100%;
            border: none;
            margin-bottom: 15px;
        }

        .identity-table td {
            border: none !important;
            padding: 2px 0;
        }

        /* Tabel Data Barang */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid black;
            padding: 8px;
        }

        .main-table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        /* Footer Tanda Tangan Hirarki */
        .footer-section {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            border: none !important;
        }

        .footer-section td {
            border: none !important;
            text-align: center;
            vertical-align: top;
            width: 50%;
            padding: 0;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    @php \Carbon\Carbon::setLocale('id'); @endphp

    <table class="kop-table">
        <tr>
            <td style="width: 80px;"><img src="{{ public_path('images/logo-garjo-smk.png') }}" style="width: 80px; height: auto;"></td>
            <td style="text-align: center;">
                <h3 style="margin: 0; font-size: 14pt;">YAYASAN SYUBBANUL WATHON</h3>
                <h3 style="margin: 0; font-size: 14pt;">SMK SYUBBANUL WATHON TEGALREJO</h3>
                <p style="margin: 2px 0; font-size: 10pt;">PONDOK PESANTREN API ASRI</p>
                <p style="margin: 2px 0; font-size: 9pt;">Jalan K. Abdan 03 Tepo Dlimas Tegalrejo Magelang 56192 Telp. (0293) 3149001</p>
            </td>
            <td style="width: 80px;"></td>
        </tr>
    </table>

    <div class="title-box">
        Form Rencana Pengajuan Sarpras IT<br>
        SMK Syubbanul Wathon Tegalrejo Magelang
    </div>

    <div style="margin-bottom: 15px;">
        Dengan hormat,<br>
        Yang bertanda tangan di bawah ini:
        <table class="identity-table">
            <tr>
                <td style="width: 80px;">Nama</td>
                <td>: {{ $firstRecord->requested_by }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: {{ $firstRecord->position }}</td>
            </tr>
        </table>
        Mengingat kekurangan sarpras untuk kepentingan kelengkapan perbaikan, maka kami meminta Waka Sarpras untuk mengabulkan permintaan sarpras guna untuk memperlancar kegiatan sekolah.
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Harga Total</th>
                <th>Tempat*)</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($records as $index => $item)
            <tr>
                <td align="center">{{ $index + 1 }}</td>
                <td>{{ $item->item_name }}</td>
                <td align="center">{{ $item->quantity }}</td>
                <td align="right">Rp {{ number_format($item->estimated_price, 0, ',', '.') }}</td>
                <td align="right">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                <td>{{ $item->location?->name ?? '-' }}</td>
            </tr>
            @php $grandTotal += $item->total_price; @endphp
            @endforeach
            <tr>
                <td colspan="4" align="center"><strong>Total</strong></td>
                <td align="right"><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <p style="font-size: 9pt; font-style: italic; margin-top: 5px;">*) Diisi dengan nama ruang/tujuan penempatan jika barang sudah terbeli</p>
    <p>Demikian pengajuan ini kami buat. Atas perhatiannya kami ucapkan terima kasih.</p>

    <table class="footer-section">
        <tr>
            <td></td>
            <td>
                Tegalrejo, {{ \Carbon\Carbon::parse($firstRecord->requested_at)->translatedFormat('d F Y') }}<br>
                Yang Mengajukan,
                <div class="signature-space"></div>
                <span class="signature-name">{{ $firstRecord->requested_by }}</span>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="height: 20px;"></td>
        </tr>

        <tr>
            <td>
                Mengetahui,<br>
                Waka Sarpras
                <div class="signature-space"></div>
                <span class="signature-name">Nauwaf Mu'arif, S.Kom</span>
            </td>
            <td>
                <br>
                Ka. UPT
                <div class="signature-space"></div>
                <span class="signature-name">Ahmad Kuswanto, A.Md</span>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="height: 20px;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center;">
                Menyetujui,<br>
                Kepala Sekolah
                <div class="signature-space"></div>
                <span class="signature-name">Mohammad Solihin, S.Pd.I</span>
            </td>
        </tr>
    </table>
</body>

</html>