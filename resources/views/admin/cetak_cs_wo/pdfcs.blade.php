@foreach ($data as $kelompokData)
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Daftar Tagihan Pembiayaan</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid black;
                text-align: center;
                padding: 5px;
            }

            th {
                background-color: #f2f2f2;
            }

            .page-break {
                page-break-before: always;
            }
        </style>
    </head>

    <body>
        <h3>KOPERASI JASA KEUANGAN SYARIAH NUR INSANI</h3>
        <h3>ALMAT {{ $kelompokData['kelompok']['alamat'] }}</h3>
        <h3>DAFTAR TAGIHAN PEMBIAYAAN</h3>
        <table style="border-collapse: collapse; width: 100%; table-layout: fixed; border: none;">
            <tr style="border: none;">
                <td style="border: none; padding: 8px; text-align: left; width: 30%;"><strong>Nama Kelompok</strong></td>
                <td style="border: none; padding: 8px; text-align: left;">
                    {{ $kelompokData['kelompok']['nama_kelompok'] }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 8px; text-align: left; width: 30%;"><strong>Tanggal</strong></td>
                <td style="border: none; padding: 8px; text-align: left;">
                    {{ $kelompokData['kelompok']['hari'] }}/{{ $kelompokData['kelompok']['tanggal'] }}
                </td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 8px; text-align: left; width: 30%;"><strong>Nama Petugas</strong></td>
                <td style="border: none; padding: 8px; text-align: left;">
                    {{ $kelompokData['kelompok']['nama_petugas'] }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 8px; text-align: left; width: 30%;"><strong>Area Pemasaran</strong>
                </td>
                <td style="border: none; padding: 8px; text-align: left;">
                    {{ $kelompokData['kelompok']['area_pemasaran'] }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 8px; text-align: left; width: 30%;"><strong>Kode Kelompok</strong>
                </td>
                <td style="border: none; padding: 8px; text-align: left;">
                    {{ $kelompokData['kelompok']['kode_kelompok'] }}</td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Rek</th>
                    <th>Nama Anggota</th>
                    <th>Plafond</th>
                    <th>Margin</th>
                    <th>Ke</th>
                    <th>PB</th>
                    <th>Pembiayaan</th>
                    <th>twm</th>
                    <th>ft</th>
                    <th>Tunggakan</th>
                    <th>Angsuran</th>
                    <th>Setoran</th>
                    <th>Nyata</th>
                    <th>ttd</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kelompokData['anggota'] as $index => $ang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $ang->no_anggota }}</td>
                        <td>{{ $ang->nama }}</td>
                        <td>{{ number_format($ang->plafond, 0, ',', '.') }}</td>
                        <td>{{ number_format($ang->saldo_margin, 0, ',', '.') }}</td>
                        <td>{{ $ang->run_tenor }}</td>
                        <td>{{ $ang->ke }}</td>
                        <td>{{ number_format($ang->os, 0, ',', '.') }}</td>
                        <td>{{ number_format($ang->twm, 0, ',', '.') }}</td>
                        <td></td>
                        <td>{{ number_format($ang->tunggakan, 0, ',', '.') }}</td>
                        <td>{{ number_format($ang->angsuran, 0, ',', '.') }}</td>
                        <td>{{ number_format($ang->bulat, 0, ',', '.') }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align: right;">Jumlah:</th>
                    <th>{{ number_format($kelompokData['totals']['plafond'], 0, ',', '.') }}</th>
                    <th>{{ number_format($kelompokData['totals']['saldo_margin'], 0, ',', '.') }}</th>
                    <th></th>
                    <th></th>
                    <th>{{ number_format($kelompokData['totals']['os'], 0, ',', '.') }}</th>
                    <th>{{ number_format($kelompokData['totals']['twm'], 0, ',', '.') }}</th>
                    <th></th>
                    <th>{{ number_format($kelompokData['totals']['tunggakan'], 0, ',', '.') }}</th>
                    <th>{{ number_format($kelompokData['totals']['angsuran'], 0, ',', '.') }}</th>
                    <th>{{ number_format($kelompokData['totals']['bulat'], 0, ',', '.') }}</th>
                    <th colspan="2" style="background-color: #ffffff; border: none;"></th>
                </tr>
            </tfoot>
        </table>
        <table style="margin-top: 20px; width: 100%; border: none;">
            <tr style="border: none;">
                <td style="border: none; text-align: center; width: 33%;">
                    <strong>Diserahkan Oleh</strong>
                    <br><br><br><br>
                    (_____________________) &nbsp;&nbsp;&nbsp; (____________)
                    <br>
                    Ketua Kelompok
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AO/SAO
                </td>
                <td style="border: none; text-align: center; width: 33%;">
                    <strong>Diperiksa Oleh</strong>
                    <br><br><br><br>
                    (__________________)
                    <br> Marketing Manager
                </td>
                <td style="border: none; text-align: center; width: 33%;">
                    <strong>Diketahui Oleh</strong>
                    <br><br><br><br>
                    (__________________)
                    <br>BM
                </td>
            </tr>
        </table>

    </body>

    </html>
@endforeach
