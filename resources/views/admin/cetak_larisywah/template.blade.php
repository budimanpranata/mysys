<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SURAT LA RISYWAH </title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
    }

    table {
      width: 100%;
      margin: 20px auto;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid black;
      padding: 10px;
      text-align: center;
    }

    th {
      background-color: #f2f2f2;
    }

    .title {
      font-weight: bold;
      text-decoration: underline;
    }

    .subtitle {
      font-size: 16px;
      font-weight: bold;
    }

    .quote {
      font-style: italic;
    }

    .total {
      font-weight: bold;
      text-align: right;
    }
  </style>
</head>

<body>
  <h2 class="title">SURAT PERNYATAAN (La Ryswah)</h2>

  <p class="subtitle">Hukum Riswah</p>

  <p>Rasulullah SAW Bersabda :</p>
  <p class="quote">"Rasulullah melaknat penyuap dan yang menerima suap" (HR. Khasmah kecuali an-Nasai dan disahihkan
    oleh at-Tirmidzi):</p>

  <p><strong>Bukti penerimaan pembiayaan kelompok {{ $namaKelompok }}</strong></p>

  <table>
    <tr>
      <th>No</th>
      <th>No.Rek</th>
      <th>Nama Nasabah</th>
      <th>Plafond</th>
      <th>Total Terima</th>
      <th> TTD </th>
    </tr>

    @foreach($results as $index => $result)
    <tr>
      <td>{{ $index + 1 }}</td>
      <td>{{ $result->no_anggota }}</td>
      <td>{{ $result->nama }}</td>
      <td>{{ $result->formattedPlafond }}</td>
      <td></td>
      <td></td>
    </tr>
    @endforeach

    <tr>
      <td colspan="3" class="total">Jumlah</td>
      <td><strong>{{ $totalPlafond }}</strong></td>
      <td></td>
      <td></td>
    </tr>
  </table>
</body>

</html>