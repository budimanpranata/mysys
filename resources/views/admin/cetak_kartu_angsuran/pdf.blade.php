@foreach ($data as $item)

    @php
        $param_tanggal = $item->tgl_murab;
        $hari = \Carbon\Carbon::parse($param_tanggal)->locale('id')->isoFormat('dddd');
        $tanggal = \Carbon\Carbon::parse($param_tanggal)->locale('id')->isoFormat('D MMMM Y');
        $jatuh_tempo = \Carbon\Carbon::parse($param_tanggal)->addWeeks($item->tenor)->locale('id')->isoFormat('D MMMM Y'); // tambahkan 25 minggu
    @endphp

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kartu Angsuran dan Tabungan</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid black;
                padding: 8px;
                text-align: center;
            }

            th {
                background-color: #f2f2f2;
            }

            h3 {
                text-align: center;
            }
        </style>
    </head>

    <body>
        <h3>DAFTAR KARTU ANGSURAN DAN TABUNGAN</h3>

        <p>Nomor Rekening: {{ $item->norek }}</p>
        <p>Nama: {{ $item->nama_anggota }}</p>
        <p>Plafond Pembiayaan: {{ number_format($item->plafond) }}</p>
        <p>Tanggal Realisasi: {{ $tanggal }}</p>
        <p>Jangka Waktu: {{ $item->tenor }} Minggu</p>
        <p>Tanggal Jatuh Tempo: {{ $jatuh_tempo }}</p>
        <p>Simpanan Pokok: {{ $item->pokok }}</p>
        <p>Simpanan Wajib: {{ $item->os }}</p>
        <p>Nama Kelompok: {{ $item->nama_kel }}</p>

        <table class="table-sm">
            <thead>
                <tr>
                    <th>KE</th>
                    <th>Tgl Transaksi</th>
                    <th>Setoran</th>
                    <th>Angsuran</th>
                    <th>Tabungan Pencairan</th>
                    <th>Tabungan Wajib</th>
                    <th>Tabungan Saldo</th>
                    <th>Paraf Anggota</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i <= $item->tenor; $i++)
                    <tr>
                        @php
                            $persen = $item->plafond * 0.18;
                            $angsuran = ($item->plafond + $persen) / $item->tenor + 2500;
                            $setoran = $item->bulat;
                            $wajib = $item->bulat - $angsuran;
                        @endphp
                        <td>{{ $i }}</td>
                        <td></td>
                        <td>{{ number_format($angsuran) }}</td>
                        <td>{{ number_format($setoran) }}</td>
                        <td></td>
                        <td>{{ number_format($wajib) }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </body>

    </html>
@endforeach
