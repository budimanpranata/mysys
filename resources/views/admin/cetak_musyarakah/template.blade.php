@foreach($results as $result)
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PEMBIAYAAN AKAD MUSYARAKAH</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      line-height: 1.6;
    }

    h1,
    h2 {
      text-align: center;
      text-transform: uppercase;
    }

    p {
      text-align: justify;
    }

    .text-align-center {
      text-align: center;
    }

    .signature {
      margin-top: 30px;
      width: 100%;
    }

    .signature table {
      width: 100%;
      border-collapse: collapse;
    }

    .signature td {
      text-align: center;
      vertical-align: middle;
    }
  </style>
</head>

<body>
  <h1>PEMBIAYAAN AKAD MUSYARAKAH</h1>
  <p class="text-align-center"><strong>No: {{ $result->no_anggota }}</strong></p>

  <p>Dengan memohon petunjuk dan ridha Allah, pada hari ini {{ $result->namaHari }} tanggal {{ $result->tanggal }} bulan
    {{
    $result->bulan }}, tahun {{ $result->tahun }}, yang bertanda tangan di bawah ini :</p>

  <p>
    Nama {{ $result->namaMM }}, jabatan <strong>Marketing Manager (MM)</strong> dalam hal ini bertindak untuk dan
    atas nama <strong>Koperasi Simpan Pinjam dan Pembiayaan Syariah (KSPPS) Nur Insani</strong>, yang berkedudukan di
    kota Tangerang Selatan berdasarkan Akta Pendirian No 02 tanggal 7 bulan Mei tahun 2011 melalui notaries Neilly
    Iralita Iswari, SH, MSi, MKn dan telah memperoleh persetujuan Kementerian Koperasi dan Usaha Kecil dan Menengah No.
    1024/BH/M.KUM.2/XI/2011, serta dengan perubahan-perubahannya, selanjutnya disebut sebagai <strong>PIHAK
      PERTAMA</strong>.
  </p>

  <p>
    Nyonya {{ $result->anggotaNama }} bertempat tinggal di {{ $result->desa }}, {{ $result->kecamatan }} Pemegang KTP
    dengan
    NIK {{ $result->ktp }}, selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong>.
  </p>

  <p>PIHAK PERTAMA dan PIHAK KEDUA selanjutnya bersama-sama disebut 'Para Pihak', sepakat untuk membuat
    dan menandatangani pembiayaan Musyarakah, selanjutnya disebut 'Akad' dengan syarat dan ketentuan sebagai berikut:
  </p>

  <h2>PASAL 1<br>KETENTUAN POKOK AKAD</h2>
  <p>Ketentuan-ketentuan pokok Akad ini meliputi sebagai berikut :</p>
  <ol>
    <li>Jenis Usaha: {{ $result->nama_usaha }}</li>
    <li>Modal Pihak Pertama: {{ $result->totalPinjaman }} ({{ $result->totalPinjamanText }})</li>
    <li>Prosentase Margin Pihak Pertama: {{ $result->persentaseMarginNI }}</li>
    <li>Prosentase Margin Pihak Kedua: {{ $result->persentaseMargin }}</li>
    <li>Jumlah Angsuran Mingguan: {{ $result->nominalAngsuran }} ({{ $result->nominalAngsuranText }})</li>
    <li>Hari Pembayaran Angsuran: Setiap hari {{ $result->hari }}</li>
    <li>Jangka Waktu Pembiayaan: {{ $result->tenor }} Minggu</li>
    <li>Tanggal Jatuh Tempo Pembiayaan: Tanggal {{ $result->maturityTanggal }} {{ $result->maturityBulan }} {{
      $result->maturityTahun }}</li>
  </ol>

  <h2>PASAL 2<br>JENIS USAHA</h2>
  <p>Usaha sebagaimana dimaksud Pasal 1 huruf a adalah aktivitas yang tidak bertentangan dengan prinsip-prinsip syariah.
  </p>

  <h2>PASAL 3<br>MODAL PIHAK PERTAMA</h2>
  <p>Pelaksanaan prinsip Musyarakah yang berlangsung antara PIHAK PERTAMA dengan PIHAK KEDUA, dilaksanakan sebagai
    berikut:</p>
  <ol>
    <li>PIHAK KEDUA meminta kepada PIHAK PERTAMA untuk menyediakan modal.</li>
    <li>PIHAK PERTAMA memberikan modal kepada PIHAK KEDUA sesuai dengan pertimbangan dari pembiayaan terakhir dan omset
      terkecilnya.</li>
  </ol>

  <h2>PASAL 4<br>SYARAT PEMBERIAN MODAL</h2>
  <ol>
    <li>PIHAK KEDUA telah menyerahkan semua persyaratan yang diminta kepada PIHAK PERTAMA.</li>
    <li>PIHAK PERTAMA dan PIHAK KEDUA telah menandatangani perjanjian ini.</li>
  </ol>
  <p>Selanjutnya PIHAK KEDUA dengan ini mengakui dengan sebenarnya dan secara sah menerima modal tersebut dan menyatakan
    diri secara sah bahwa PIHAK PERTAMA adalah partner dalam bidang usahanya. </p>

  <h2>PASAL 5<br>KUASA</h2>
  <p>PIHAK PERTAMA memberikan kuasa kepada PIHAK KEDUA untuk melakukan usaha sesuai dengan apa yang diinginkan. Dalam
    hal ini, PIHAK KEDUA sebagai wakil dalam usaha bagi PIHAK PERTAMA.</p>

  <h2>PASAL 6<br>SURAT PERNYATAAN SUKA RELA</h2>
  <p>PIHAK KEDUA menyatakan dengan sukarela dan kesadaran sendiri untuk :</p>
  <ol>
    <li>Akan membayar angsuran tepat waktu sesuai tempat dan waktu yang telah disepakati.</li>
    <li>Tidak menggunakan pembiayaan ini untuk kepentingan pihak lain. Apabila di kemudian hari terbukti digunakan oleh
      pihak lain, akan tetap bertanggung jawab membayar angsuran tepat waktu</li>
    <li>Tidak melibatkan pihak luar (LSM, Ormas atau sejenisnya) untuk tidak membayar angsuran. Apabila hal diatas tidak
      dipenuhi, maka PIHAK KEDUA bersedia diiaporkan kepada pihak berwajib sesuai undang-undang yang berlaku.</li>
  </ol>

  <h2>PASAL 7<br>PERHITUNGAN PADA AKHIR PELUNASAN</h2>
  <p>Pada akhir pelunasan akan diadakan penghitungan ulang, apabila ada margin yang lebih diambil oleh PIHAK PERTAMA
    setelah diprediksi pada awal akad, maka akan dikembalikan kepada PIHAK KEDUA. Namun jika margin yang diambil oleh
    PIHAK PERTAMA kurang, maka kekurangan itu akan diberikan secara suka rela kepada PIHAK KEDUA. </p>

  <h2>PASAL 8<br>PASAL TAMBAHAN</h2>
  <p>Hal-hal lain yang belum cukup diatur dalam perjanjian ini, akan diatur berdasarkan kesepakatan PARA PIHAK ke dalam
    surat/akta yang merupakan satu kesatuan dengan perjanjian ini. Demikian Perjanjian ini dibuat dan ditandatangani di
    desa {{ $result->desa }} pada hari {{ $result->namaHari }} tanggal {{ $result->tanggal }} bulan {{
    $result->bulan }}, tahun {{ $result->tahun }}
  </p>

  <div class="signature">
    <table style="width: 100%; margin-top: 50px; text-align: center;">
      <tr>
        <td>
          <p class="text-align-center">PIHAK PERTAMA</p>
          <p class="text-align-center">(.................................)<br>{{ $result->namaMM }}</p>
        </td>
        <td>
          <p class="text-align-center">PIHAK KEDUA</p>
          <p class="text-align-center">(.................................)<br>{{ $result->anggotaNama }}</p>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>
@endforeach