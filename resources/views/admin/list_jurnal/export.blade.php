
<p style="align-content: center">List Jurnal</p>

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Nomor Bukti</th>
            <th>Kode Rekening</th>
            <th>Debet</th>
            <th>Kredit</th>
            <th>Posisi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td>{{ $row->tanggal_transaksi }}</td>
            <td>{{ $row->kode_transaksi }}</td>
            <td>{{ number_format($row->debet, 2) }}</td>
            <td>{{ number_format($row->kredit, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
