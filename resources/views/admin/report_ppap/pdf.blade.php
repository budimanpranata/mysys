<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Kolektabilitas Pembiayaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }
        .header {
            text-align: left;
            margin-bottom: 20px;
        }
        .header h3 {
            margin: 5px 0;
            font-size: 12px;
            font-weight: bold;
        }
        .header p {
            margin: 3px 0;
            font-size: 9px;
        }
        .title {
            text-align: left;
            margin: 20px 0;
        }
        .title h4 {
            margin: 5px 0;
            font-size: 11px;
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th {
            background-color: #f0f0f0;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }
        td {
            padding: 6px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .signature-section {
            margin-top: 40px;
            width: 100%;
        }
        .signature-table {
            width: 100%;
            border: none;
        }
        .signature-table td {
            border: 1px solid black;
            padding: 40px 10px 10px 10px;
            text-align: center;
            width: 33.33%;
        }
        .no-wrap {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>{{ strtoupper('KOPERASI JASA KEUANGAN SYARIAH NUR INSANI') }} UNIT {{ strtoupper($branch->unit ?? '(nama ap)') }}</h3>
        <p>{{ strtoupper($branch->alamat ?? '(alamat)') }}</p>
    </div>

    <div class="title">
        <h4>REKAP KOLEKTABILITAS PEMBIAYAAN</h4>
        <p><strong>Tanggal {{ \Carbon\Carbon::parse($tanggal_cetak)->isoFormat('DD MMMM YYYY') }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kelompok Kolektabilitas</th>
                <th>Jumlah Debitur</th>
                <th>Nilai Anggunan</th>
                <th>Plafond</th>
                <th>Saldo Pinjaman</th>
                <th>Prosent NPL-Gross</th>
                <th>PPAP</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td><strong>Kredit Lancar</strong></td>
                <td class="text-center">{{ number_format($summary['lancar']['jumlah_debitur'], 0, ',', '.') }}</td>
                <td class="text-right"></td>
                <td class="text-right">{{ number_format($summary['lancar']['plafond'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($summary['lancar']['saldo_pinjaman'], 0, ',', '.') }}</td>
                <td class="text-right"></td>
                <td class="text-right">{{ number_format($summary['lancar']['ppap'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td><strong>Kredit Kurang Lancar</strong></td>
                <td class="text-center">{{ number_format($summary['kurang_lancar']['jumlah_debitur'], 0, ',', '.') }}</td>
                <td class="text-right"></td>
                <td class="text-right">{{ number_format($summary['kurang_lancar']['plafond'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($summary['kurang_lancar']['saldo_pinjaman'], 0, ',', '.') }}</td>
                <td class="text-right"></td>
                <td class="text-right">{{ number_format($summary['kurang_lancar']['ppap'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-center">3</td>
                <td><strong>Kredit Diragukan</strong></td>
                <td class="text-center">{{ number_format($summary['diragukan']['jumlah_debitur'], 0, ',', '.') }}</td>
                <td class="text-right"></td>
                <td class="text-right">{{ number_format($summary['diragukan']['plafond'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($summary['diragukan']['saldo_pinjaman'], 0, ',', '.') }}</td>
                <td class="text-right"></td>
                <td class="text-right">{{ number_format($summary['diragukan']['ppap'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-center">4</td>
                <td><strong>Kredit Macet</strong></td>
                <td class="text-center">{{ number_format($summary['macet']['jumlah_debitur'], 0, ',', '.') }}</td>
                <td class="text-right"></td>
                <td class="text-right">{{ number_format($summary['macet']['plafond'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($summary['macet']['saldo_pinjaman'], 0, ',', '.') }}</td>
                <td class="text-right"></td>
                <td class="text-right">{{ number_format($summary['macet']['ppap'], 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="2" class="text-center"><strong>Jumlah Total</strong></td>
                <td class="text-center"><strong>{{ number_format($total['jumlah_debitur'], 0, ',', '.') }}</strong></td>
                <td class="text-right"></td>
                <td class="text-right"><strong>{{ number_format($total['plafond'], 0, ',', '.') }}</strong></td>
                <td class="text-right"><strong>{{ number_format($total['saldo_pinjaman'], 0, ',', '.') }}</strong></td>
                <td class="text-right"></td>
                <td class="text-right"><strong>{{ number_format($total['ppap'], 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <strong>DIBUAT OLEH</strong>
                    <br><br><br><br>
                    _____________________
                </td>
                <td>
                    <strong>DIPERIKSA OLEH</strong>
                    <br><br><br><br>
                    _____________________
                </td>
                <td>
                    <strong>DISETUJUI OLEH</strong>
                    <br><br><br><br>
                    _____________________
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 40px;">
        <table class="signature-table">
            <tr>
                <td>
                    <strong>NAMA OFFICER</strong>
                    <br><br><br><br>
                    _____________________
                </td>
                <td>
                    <strong>ATASAN LANGSUNG</strong>
                    <br><br><br><br>
                    _____________________
                </td>
                <td>
                    <strong>ATASAN ATASAN LANGSUNG</strong>
                    <br><br><br><br>
                    _____________________
                </td>
            </tr>
        </table>
    </div>
</body>
</html>