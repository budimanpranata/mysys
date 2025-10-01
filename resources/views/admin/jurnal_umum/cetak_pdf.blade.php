<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Jurnal</title>
    <style>
        body { 
            font-family: Arial,sans-serif;
            margin: 20px;
            font-size: 12px; 
        }
        .header { 
            display: flex;
            align-items: center;
            justify-content: space-between; 
        }
        .logo img { 
            width: 120px; 
        }
        .title { 
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            margin-top: 10px;
        }
        .info { 
            margin-top: 10px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px; }
        th, td { 
            border: 1px solid black; 
            padding: 6px; 
            text-align: center; 
        }
        th { 
            background: #f2f2f2; 
        }
        .text-left { 
            text-align: left; 
        }
        .text-right { 
            text-align: right; 
        }
        .sign-table td { 
            height: 80px; 
            vertical-align: top; 
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('assets/img/logo-web-kspps-ni-black.png') }}" alt="Logo">
        </div>
    </div>
    
    <div class="title">Bukti Jurnal Umum</div>

    <div class="info">
        <p>
            No : {{ $transaksi[0]['kode_transaksi'] ?? '-' }} <br>
            Tgl : {{ \Carbon\Carbon::parse($transaksi[0]['tanggal_transaksi'])->format('d-m-Y') }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:25%">Kode Rekening</th>
                <th style="width:35%">Keterangan</th>
                <th style="width:20%">Debet</th>
                <th style="width:20%">Kredit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $row)
                <tr>
                    <td class="text-left">{{ $row['kode_rekening'] }}</td>
                    <td class="text-left">{{ $row['keterangan_transaksi'] }}</td>
                    <td class="text-right">{{ $row['posisi']=='debet' ? number_format($row['jumlah'],0,',','.') : '-' }}</td>
                    <td class="text-right">{{ $row['posisi']=='kredit' ? number_format($row['jumlah'],0,',','.') : '-' }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2"><strong>TOTAL</strong></td>
                <td class="text-right"><strong>{{ number_format(collect($transaksi)->where('posisi','debet')->sum('jumlah'),0,',','.') }}</strong></td>
                <td class="text-right"><strong>{{ number_format(collect($transaksi)->where('posisi','kredit')->sum('jumlah'),0,',','.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <table class="sign-table" style="width:100%; margin-top:30px;">
        <tr>
            <td style="width:50%; border:1px solid black; text-align:center;">
                <strong>Dibuat :</strong><br><br><br><br>
            </td>
            <td style="width:50%; border:1px solid black; text-align:center;">
                <strong>Diperiksa :</strong><br><br><br><br>
            </td>
        </tr>
    </table>

</body>
</html>
