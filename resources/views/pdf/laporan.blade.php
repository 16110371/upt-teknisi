<h2>Laporan UPT</h2>

<table width="100%" border="1" cellpadding="6">
    <tr>
        <th>Tanggal</th>
        <th>Peminta</th>
        <th>Lokasi</th>
        <th>Kategori</th>
        <th>Status</th>
        <th>Teknisi</th>
    </tr>

    @foreach ($reports as $r)
        <tr>
            <td>{{ \Carbon\Carbon::parse($r->request_date)->format('d M Y') }}</td>
            <td>{{ $r->requester_name }}</td>
            <td>{{ $r->location->name }}</td>
            <td>{{ $r->category->name }}</td>
            <td>{{ $r->status }}</td>
            <td>{{ $r->technician->name ?? '-' }}</td>
        </tr>
    @endforeach
</table>

<br><br>

<table width="100%">
    <tr>
        <td width="60%"></td>
        <td width="40%" style="text-align: center;">
            <p>
                Magelang, {{ $printed_at->translatedFormat('d F Y') }}
            </p>

            <p><strong>Kepala UPT</strong></p>

            <br><br><br>

            <p>
                <u>Ahmad Kuswanto, Amd</u><br>
            </p>
        </td>
    </tr>
</table>
