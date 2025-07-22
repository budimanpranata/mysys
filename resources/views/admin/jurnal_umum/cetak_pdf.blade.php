{{-- @php dd($transaksi); @endphp --}}

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Jurnal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo img {
            width: 70px;
            margin-right: 10px;
        }

        .title {
            font-weight: bold;
            font-size: 18px;
            color: green;
        }

        .info {
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        .no-border {
            border: none;
        }

        .keterangan {
            margin-top: 20px;
        }

    </style>
</head>

<body>

    <div class="header">
        <div class="logo">
            <img style="width: 200px" src="assets\img\logo-web-kspps-ni-black.png" alt="Logo">
        </div>
    </div>

    <div class="info">
        <p>No : {{ $transaksi[0]['kode_transaksi'] ?? '-' }}<br>Tgl : {{ \Carbon\Carbon::parse($transaksi[0]['tanggal_transaksi'])->format('d-m-Y') }} </p>
    </div>

    <h3>DEBET</h3>

    <table>
        <tr>
            <th style="align-items: center">No.Rek</th>
            <th>Perkiraan Lawan</th>
        </tr>
        <tr>
            <td>{{ $transaksi[0]['kode_rekening'] }} -  Rp. {{ number_format($transaksi[0]['jumlah'], 0, ',', '.') }}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>{{ $transaksi[0]['kode_rekening'] }} - Selisih kas Kurang Rp. {{ number_format($transaksi[0]['jumlah'], 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="keterangan">
        <p><strong>Keterangan:</strong> {{ $transaksi[0]['keterangan_transaksi'] }}</p>
    </div>

    <table style="width: 100%; margin-top: 40px;">
        <tr>
            <td style="width: 50%; border: 1px solid black; height: 100px; text-align: center; vertical-align: top;">
                <strong>Dibuat :</strong><br><br><br>
            </td>
            <td style="width: 50%; border: 1px solid black; height: 100px; text-align: center; vertical-align: top;">
                <strong>Diperiksa :</strong><br><br><br>
            </td>
        </tr>
    </table>



</body>

</html>