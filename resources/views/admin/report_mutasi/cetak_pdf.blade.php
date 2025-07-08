<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutasi Simpanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            font-weight: bold;
        }
        .logo {
            display: block;
            margin: 0 auto;
            width: 240px;
        }
        .info {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .info-table, .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10pt;
        }
        .info-table td {
            padding: 5px;
        }
        .data-table th, .data-table td {
            border: 1px solid black;
            padding: 5px;
            /* text-align: center; */
        }
        .data-table th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

    
    <div class="header">
        <img src="{{ asset('/img/logo-web-kspps-ni-black.png') }}" alt="Logo Koperasi" class="logo">
        <p>
            KOPERASI SIMPAN PINJAM SYARIAH NUR INSANI<br>
            <h3>DAFTAR MUTASI SIMPANAN ANGGOTA</h3>
        </p>
    </div>

    <div class="info">
        <table class="info-table">
            <tr>
                <td>Nomor Rekening</td>
                <td>: {{ $anggota->norek ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>: {{ $anggota->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>Plafond Pembiayaan</td>
                <td>: {{ number_format($anggota->plafond ?? '0') }}</td>
            </tr>
            <tr>
                <td>Tanggal Akad</td>
                <td>: {{ date('d M Y', strtotime($anggota->tgl_join ?? '-')) }}</td>
            </tr>
            <tr>
                <td>Jangka Waktu</td>
                <td>: {{ $anggota->tenor }} Minggu</td>
            </tr>
            <tr>
                <td>Tanggal Jatuh Tempo</td>
                <td>: {{ date('d M Y', strtotime($anggota->maturity_date ?? '-')) }}</td>
            </tr>
            <tr>
                <td>Sisa Pembiayaan</td>
                <td>: {{ number_format($anggota->os) ?? '0' }}</td>
            </tr>
            <tr>
                <td>Simpanan Pokok</td>
                <td>: {{ number_format($anggota->pokok) ?? '0' }}</td>
            </tr>
            <tr>
                <td>Simpanan Wajib</td>
                <td colspan="3">: {{ number_format($anggota->bulat) ?? '0' }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Transaksi</th>
                <th>Keterangan</th>
                <th>Tipe</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

</body>
</html>
