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

            .info {
            margin-bottom: 20px;
            line-height: 1.6;
            }
            .info span {
                display: block;
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

            .info td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            h3 {
                text-align: center;
            }

            .tabel-info {
                border-collapse: collapse;
                width: 100%;
                border: none;
            }

            .tabel-info td, 
            .tabel-info th {    
                border: none;
                padding: 1px;
            }

        </style>
    </head>

    <body>
        <h3>DAFTAR KARTU ANGSURAN DAN TABUNGAN</h3>
        
        <div class="info">
            <table class="tabel-info">
                <tr>
                    <td style="width: 260px;">Nomor Rekening</td>
                    <td>:</td>
                    <td>{{ $item->norek }}</td>
                </tr>
                <tr>
                    <td style="width: 260px;">Nama</td>
                    <td>:</td>
                    <td>{{ $item->nama_anggota }}</td>
                </tr>
                <tr>
                    <td style="width: 260px;">Plafond Pembiayaan</td>
                    <td>:</td>
                    <td>{{ number_format($item->plafond) }}</td>
                </tr>
                <tr>
                    <td style="width: 260px;">Tanggal Realisasi</td>
                    <td>:</td>
                    <td>{{ $tanggal }}</td>
                </tr>
                <tr>
                    <td style="width: 260px;">Tanggal Jatuh Tempo</td>
                    <td>:</td>
                    <td>{{ $jatuh_tempo }}</td>
                </tr>
                <tr>
                    <td style="width: 260px;">Simpanan Pokok</td>
                    <td>:</td>
                    <td>{{ number_format($item->pokok) }}</td>
                </tr>
                <tr>
                    <td style="width: 260px;">Simpanan Wajib</td>
                    <td>:</td>
                    <td>{{ number_format($item->os) }}</td>
                </tr>
                <tr>
                    <td style="width: 260px;">Nama Kelompok</td>
                    <td>:</td>
                    <td>{{ $item->nama_kel }}</td>
                </tr>
            </table>
        </div>

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
