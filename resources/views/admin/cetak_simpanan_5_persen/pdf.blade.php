@php
    $param_tanggal = $data_kel->tgl_akad;
    $hari = \Carbon\Carbon::parse($param_tanggal)->locale('id')->isoFormat('dddd');
    $tanggal = \Carbon\Carbon::parse($param_tanggal)->locale('id')->isoFormat('D MMMM Y');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Simpanan 5%</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
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
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th {
            text-align: center;
            padding: 8px;
        }
        td {
            padding: 8px;

        }
        
        .footer {
            display: flex; /* Mengaktifkan flexbox */
            align-items: center; /* Menjaga elemen sejajar vertikal */
            margin-top: 180px;
        }

        .footer-kanan {
            text-align: right; /* Agar teks di tengah-tengah setiap kolom */
        }

        .footer-kiri {
            margin-top: -80px;
            text-align: left; /* Agar teks di tengah-tengah setiap kolom */
        }


    </style>
</head>
<body>
    <div class="header">
        DAFTAR SIMPANAN 5 %
    </div>

    <div class="info">
        <span>Nama Kelompok : {{ $data_kel->nama_kel }}</span>
        <span>Hari/Tanggal  : {{ $hari }}/{{ $tanggal }}</span>
        <span>Nama Petugas  : {{ $data_kel->nama_ao }}</span>
        <span>Area Pemasaran: {{ $data_kel->kode_unit }}</span>
        <span>Kode Kelompok : {{ $data_kel->code_kel }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Rek</th>
                <th>Nama Nasabah</th>
                <th>Plafond</th>
                <th>Simpanan 5%</th>
                <th>TTD</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_plafond = 0;
                $total_simpanan_lima_persen = 0;
            @endphp
        
            @foreach ($data as $item)
                @php
                    $simpanan_lima_persen = $item->plafond * 0.05;
                    $total_plafond += $item->plafond;
                    $total_simpanan_lima_persen += $simpanan_lima_persen;
                @endphp
        
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->norek }}</td>
                    <td>{{ $item->nama_anggota }}</td>
                    <td style="text-align: right">{{ number_format($item->plafond, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($simpanan_lima_persen, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            @endforeach
        
            <tr>
                <th colspan="3">Total</th>
                <th style="text-align: right">{{ number_format($total_plafond, 0, ',', '.') }}</th>
                <th style="text-align: right">{{ number_format($total_simpanan_lima_persen, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tbody>        
    </table>

    <div class="footer">
        <div class="footer-kanan">
            Ketua Kelompok<br><br><br>
            (_______________)
        </div>
        <div class="footer-kiri">
            Marketing Manager<br><br><br>
            (_______________)
        </div>
    </div>
    
</body>
</html>
