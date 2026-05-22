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

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        .header h3 {
            margin: 0;
            font-size: 13pt;
        }

        .header p {
            margin: 2px 0;
            font-size: 9pt;
        }

        .title-box {
            text-align: center;
            margin: 15px 0;
            font-weight: bold;
        }

        .intro {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .footer-table {
            width: 100%;
            border: none !important;
            margin-top: 20px;
        }

        .footer-table td {
            border: none !important;
            text-align: center;
            vertical-align: top;
            padding: 0;
        }

        .spacer {
            height: 60px;
        }

        .note {
            font-size: 9pt;
            font-style: italic;
            margin-top: -10px;
        }

        /* Style khusus untuk tabel tanpa garis (Header & Footer) */
        .borderless-table {
            width: 100%;
            border-collapse: collapse;
            border: none !important;
            /* Menghilangkan garis luar tabel */
        }

        .borderless-table td {
            border: none !important;
            /* Menghilangkan garis per kolom/sel */
            padding: 0;
            vertical-align: middle;
        }

        /* Tetap gunakan border untuk tabel data barang */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table th,
        .main-table td {
            border: 1px solid black;
            padding: 8px;
        }

        /* Style Tabel Kop (Tanpa garis kotak, tapi ada garis bawah ganda) */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border: none !important;
            /* Garis ganda tebal-tipis di bawah kop */
            border-bottom: 4px double #000 !important;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-table td {
            border: none !important;
            /* Menghilangkan garis kotak per kolom */
            padding: 0;
            vertical-align: middle;
        }

        /* Tabel utama tetap menggunakan garis kotak */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table th,
        .main-table td {
            border: 1px solid black;
            padding: 8px;
        }

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
            /* Membagi dua kolom utama untuk baris pertama */
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
    <table class="kop-table">
        <tr>
            <td style="width: 80px;">
                <img src="{{ public_path('images/logo-garjo-smk.png') }}" style="width: 80px; height: auto;">
            </td>
            <td style="text-align: center;">
                <h3 style="margin: 0; font-size: 14pt;">YAYASAN SYUBBANUL WATHON</h3>
                <h3 style="margin: 0; font-size: 14pt;">SMK SYUBBANUL WATHON TEGALREJO</h3>
                <p style="margin: 2px 0; font-size: 10pt;">PONDOK PESANTREN API ASRI</p>
                <p style="margin: 2px 0; font-size: 9pt;">Jalan K. Abdan 03 Tepo Dlimas Tegalrejo Magelang 56192 Telp.
                    (0293) 3149001</p>
            </td>
            <td style="width: 80px;"></td>
        </tr>
    </table>

    <div class="title-box">
        Form Rencana Pengajuan Sarpras IT<br>
        SMK Syubbanul Wathon Tegalrejo Magelang
    </div>

    <div class="intro">
        Dengan hormat,<br>
        Yang bertanda tangan di bawah ini:<br>
        <table style="border:none; margin-left: 20px">
            <tr style="border:none">
                <td style="border:none; width:80px">Nama</td>
                <td style="border:none">: {{ $record->requested_by }}</td>
            </tr>
            <tr style="border:none">
                <td style="border:none">Jabatan</td>
                <td style="border:none">: {{ $record->position }}</td>
            </tr>
        </table>
        Mengingat kekurangan sarpras untuk kepentingan kelengkapan perbaikan, maka kami meminta Waka Sarpras untuk
        mengabulkan permintaan sarpras guna untuk memperlancar kegiatan sekolah.
    </div>

    <table>
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
            <tr>
                <td align="center">1</td>
                <td>{{ $record->item_name }}</td>
                <td align="center">{{ $record->quantity }}</td>
                <td align="right">Rp {{ number_format($record->estimated_price, 0, ',', '.') }}</td>
                <td align="right">Rp {{ number_format($record->total_price, 0, ',', '.') }}</td>
                <td>{{ $record->location?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td colspan="4" align="center"><strong>Total</strong></td>
                <td align="right"><strong>Rp {{ number_format($record->total_price, 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <p class="note">*) Diisi dengan nama ruang/tujuan penempatan jika barang sudah terbeli</p>
    <p>Demikian pengajuan ini kami buat. Atas perhatiannya kami ucapkan terima kasih.</p>
    @php
        \Carbon\Carbon::setLocale('id');
    @endphp
    <table class="footer-section">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%;">
                Tegalrejo, {{ \Carbon\Carbon::parse($record->requested_at)->translatedFormat('d F Y') }}<br>
                Yang Mengajukan,
                <div class="signature-space"></div>
                <span class="signature-name"> {{ $record->requested_by }} </span>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="height: 30px;"></td>
        </tr>

        <tr>
            <td>
                Mengetahui,<br>
                Waka Sarpras
                <div class="signature-space"></div>
                <span class="signature-name"> Nauwaf Mu'arif, S.Kom </span>
            </td>
            <td>
                <br> Ka. UPT
                <div class="signature-space"></div>
                <span class="signature-name"> Ahmad Kuswanto, A.Md </span>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="height: 30px;"></td>
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
