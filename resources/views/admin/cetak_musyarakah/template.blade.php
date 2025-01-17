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
      margin: 40px;
      line-height: 1.6;
    }

    h1,
    h2 {
      text-align: center;
      text-transform: uppercase;
    }

    .section-title {
      font-weight: bold;
      margin-top: 20px;
    }

    .signature {
      display: flex;
      justify-content: space-between;
      margin-top: 50px;
    }

    .signature div {
      text-align: center;
    }

    .indent {
      text-indent: 50px;
    }
  </style>
</head>

<body>
  <h1>PEMBIAYAAN AKAD MUSYARAKAH</h1>
  <p>No: ...............................................................</p>

  <p class="indent">Dengan memohon petunjuk dan ridha Allah, pada hari ini .... tanggal .... bulan ......,
    tahun............, yang bertanda tangan di bawah ini :</p>

  <p>
    Nama ........................, jabatan <strong>Marketing Manager (MM)</strong> dalam hal ini bertindak untuk dan
    atas nama <strong>Koperasi Simpan Pinjam dan Pembiayaan Syariah (KSPPS) Nur Insani</strong>, yang berkedudukan di
    kota Tangerang Selatan berdasarkan Akta Pendirian No 02 tanggal 7 bulan Mei tahun 2011 melalui notaries Neilly
    Iralita Iswari, SH, MSi, MKn dan telah memperoleh persetujuan Kementerian Koperasi dan Usaha Kecil dan Menengah No.
    1024/BH/M.KUM.2/XI/2011, serta dengan perubahan-perubahannya, selanjutnya disebut sebagai <strong>PIHAK
      PERTAMA</strong>.
  </p>

  <p>
    Nyonya ..................... bertempat tinggal di DK ........................................ Pemegang KTP dengan
    NIK 1......................, selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong>.
  </p>

  <p class="indent">PIHAK PERTAMA dan PIHAK KEDUA selanjutnya bersama-sama disebut 'Para Pihak', sepakat untuk membuat
    dan menandatangani pembiayaan Musyarakah, selanjutnya disebut 'Akad' dengan syarat dan ketentuan sebagai berikut:
  </p>

  <h2>PASAL 1<br>KETENTUAN POKOK AKAD</h2>
  <ul>
    <li>Jenis Usaha: .................................</li>
    <li>Modal Pihak Pertama: .................................</li>
    <li>Prosentase Margin Pihak Pertama: .................................</li>
    <li>Prosentase Margin Pihak Kedua: .................................</li>
    <li>Jumlah Angsuran Mingguan: .................................</li>
    <li>Hari Pembayaran Angsuran: .................................</li>
    <li>Jangka Waktu Pembiayaan: .................................</li>
    <li>Tanggal Jatuh Tempo Pembiayaan: .................................</li>
  </ul>

  <h2>PASAL 2<br>JENIS USAHA</h2>
  <p>Usaha sebagaimana dimaksud Pasal 1 huruf a adalah aktivitas yang tidak bertentangan dengan prinsip-prinsip syariah.
  </p>

  <h2>PASAL 3<br>MODAL PIHAK PERTAMA</h2>
  <p>Pelaksanaan prinsip Musyarakah yang berlangsung antara PIHAK PERTAMA dengan PIHAK KEDUA, dilaksanakan sebagai
    berikut:</p>
  <ul>
    <li>PIHAK KEDUA meminta kepada PIHAK PERTAMA untuk menyediakan modal.</li>
    <li>PIHAK PERTAMA memberikan modal kepada PIHAK KEDUA sesuai dengan pertimbangan dari pembiayaan terakhir dan omset
      terkecilnya.</li>
  </ul>

  <h2>PASAL 4<br>SYARAT PEMBERIAN MODAL</h2>
  <ul>
    <li>PIHAK KEDUA telah menyerahkan semua persyaratan yang diminta kepada PIHAK PERTAMA.</li>
    <li>PIHAK PERTAMA dan PIHAK KEDUA telah menandatangani perjanjian ini.</li>
  </ul>

  <h2>PASAL 5<br>KUASA</h2>
  <p>PIHAK PERTAMA memberikan kuasa kepada PIHAK KEDUA untuk melakukan usaha sesuai dengan apa yang diinginkan. Dalam
    hal ini, PIHAK KEDUA sebagai wakil dalam usaha bagi PIHAK PERTAMA.</p>

  <h2>PASAL 6<br>SURAT PERNYATAAN SUKA RELA</h2>
  <ul>
    <li>Akan membayar angsuran tepat waktu sesuai tempat dan waktu yang telah disepakati.</li>
    <li>Tidak menggunakan pembiayaan ini untuk kepentingan pihak lain.</li>
    <li>Tidak melibatkan pihak luar untuk tidak membayar angsuran.</li>
  </ul>

  <h2>PASAL 7<br>PERHITUNGAN PADA AKHIR PELUNASAN</h2>
  <p>Penghitungan ulang margin dilakukan pada akhir pelunasan. Selisih margin akan disesuaikan sesuai ketentuan.</p>

  <h2>PASAL 8<br>PASAL TAMBAHAN</h2>
  <p>Hal-hal lain yang belum diatur akan disepakati lebih lanjut dalam dokumen terpisah.</p>

  <div class="signature">
    <div>
      <p>PIHAK PERTAMA</p>
      <p>(.................................)<br>Marketing Manager</p>
    </div>
    <div>
      <p>PIHAK KEDUA</p>
      <p>(.................................)<br>Ketua Kelompok</p>
    </div>
  </div>
</body>

</html>
@endforeach