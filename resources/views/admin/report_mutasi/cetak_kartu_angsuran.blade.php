<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutasi Simpanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0px;
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
            padding: 2px;
            /* text-align: center; */
        }
        .data-table th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

    
    <div class="header">
        <img src="{{ asset('assets/img/logo-web-kspps-ni-black.png') }}" alt="(Logo Koperasi)" class="logo">
        <p>
            KOPERASI SIMPAN PINJAM SYARIAH NUR INSANI<br>
            <h3>DAFTAR MUTASI KARTU ANGGOTA</h3>
        </p>
    </div>

    <div class="info">
        <table class="info-table">
            <tr>
                <td>Nomor Rekening</td>
                <td>: {{ $anggota->no_anggota ?? '-' }}</td>
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
                <td>: {{ date('d M Y', strtotime($anggota->tgl_akad ?? '-')) }}</td>
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
            @php
                $no = 1;
                $saldo = 0;
            @endphp
            @foreach ($mutasiKartuAngsuran as $kartuAngsuran)
                @php
                    $debet = $kartuAngsuran->debet ?? 0;
                    $kredit = $kartuAngsuran->kredit ?? 0;
                    $saldo += ($kredit - $debet);
                @endphp
                <tr>
                    <td>{{ $no++ }}</td>
                    <td style="text-align: left">{{ date('d-m-Y', strtotime($kartuAngsuran->buss_date)) }}</td>
                    <td>{{ $kartuAngsuran->kode_transaksi }}</td>
                    <td>{{ $kartuAngsuran->ket }}</td>
                    <td style="text-align: center">{{ $kartuAngsuran->type }}</td>
                    <td style="text-align: right">{{ number_format($debet, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($kredit, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($saldo, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
