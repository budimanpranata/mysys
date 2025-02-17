@foreach ($data as $item)
    @php
        $param_tanggal = $item->tgl_murab;
        $hari = \Carbon\Carbon::parse($param_tanggal)->locale('id')->isoFormat('dddd');
        $tanggal = \Carbon\Carbon::parse($param_tanggal)->locale('id')->isoFormat('D MMMM Y');
        $jatuh_tempo = \Carbon\Carbon::parse($param_tanggal)->addWeeks($item->tenor_pembiayaan)->locale('id')->isoFormat('D MMMM Y'); // tambahkan 25 minggu
        $rest_jatuh_tempo = \Carbon\Carbon::parse($param_tanggal)->addWeeks($item->rest_tenor)->locale('id')->isoFormat('D MMMM Y'); // tambahkan 25 minggu
    @endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CETAK ADENDUM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        .header {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .header p {
            text-align: center;
            font-size: 16px;
            font-style: italic;
        }

        .content {
            margin-top: 20px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }

        .section-content {
            margin-left: 0px;
            text-align: justify;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2><u>ADENDUM PERJANJIAN JUAL BELI (AL MURABAHAH)</u></h2>
            <p>
                "Hai Orang-orang beriman penuhilah akad-akad perjanjian itu." (Q.S. Al Maidah 1)<br>
                "Cukupkanlah takaran jangan kamu menjadi orang-orang yang merugikan." (Q.S. Asy-Syu'ara 181)<br>
                "Dan Allah SWT telah menghalalkan jual-beli dan mengharamkan riba." (Q.S. Al-Baqarah 275)
            </p>
        </div>
        <hr>
        <div class="content">
            <div class="section">
                <div class="section-content">
                    Mengacu pada Perjanjian Jual Beli (Al Murabahah) pada tanggal {{ $tanggal }} pada kelompok {{ $item->nama_kel }} mempertimbangkan adanya kesepakatan ulang antara kedua belah pihak bersepakat
                    untuk membuat Adendum Perjanjian jual beli. Dengan memohon petunjuk dan Ridho Allah SWT, pada hari
                    ini hari {{ $hari_ini }} tanggal {{ $today }}, yang bertanda tangan di bawah ini :

                    <br>
                    <br>

                    <table style="font-weight: bold;">
                        <tr>
                            <td style="width: 140px;">Nama</td>
                            <td>:</td>
                            <td>{{ $item->nama_mm }}</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{ $item->jabatan }}</td>
                        </tr>
                    </table>

                    <br>
                    <br>

                    Dalam hal ini bertindak untuk dan atas nama KOPERASI SIMPAN PINJAM DAN PEMBIAYAAN SYARIAH
                    NUR INSANI, berkedudukan di Tangerang Selatan, berdasarkan akte pendirian KJKS No : 02 Tanggal 07
                    Mei 2011 dihadapan notaris Neilly Iralita Iswari, SH, Msi, MKn. dan telah memperoleh persetujuan
                    kementrian Koperasi dan Usaha Kecil dan Menengah No. 1024/BH/MKUM2/XI/2011 serta
                    perubahan-perubahannya, selanjutnya disebut PIHAK PERTAMA.

                    <br>
                    <br>

                    <table style="font-weight: bold;">
                        <tr>
                            <td style="width: 140px;">Nama</td>
                            <td>:</td>
                            <td>{{ $item->nama_anggota }}</td>
                        </tr>
                        <tr>
                            <td>Nomor Rekening</td>
                            <td>:</td>
                            <td>{{ $item->norek }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>{{ $item->alamat }} {{ $item->desa }} {{ $item->kecamatan }} {{ $item->kota }}</td>
                        </tr>
                    </table>

                    <br>
                    <br>

                    Selanjutnya disebut PIHAK KEDUA.
                    Dalam hal ini telah mendapatkan persetujuan dari suami/Orang Tua/Penjamin sebagaimana ikut serta
                    menanda-tangani perjanjian ini.
                    PIHAK PERTAMA dan PIHAK KEDUA selanjutnya bersama-sama disebut "PARA PIHAK", menerangkan
                    sebagai berikut :
                    I. Bahwa PIHAK KEDUA telah mengajukan pembiayaan untuk pembelian barang sesuai dengan surat
                    Permohonan pembiayaan pada tanggal {{ $tanggal }}
                    II. Bahwa PIHAK PERTAMA telah menyatakan persetujuan untuk memberikan Pembiayaan Murabahah
                    kepada PIHAK KEDUA
                    Selanjutnya, PIHAK PERTAMA DAN KEDUA secara bersama-sama(selanjutnya disebut Para Pihak) sepakat
                    untuk membuat dan menandatangani perubahan Akad Murabahah (selanjutnya disebut "Adendum") ini untuk
                    dapat dan dilaksanakan dengan syarat dan ketentuan sebagai berikut :
                </div>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <div class="section">
                <div class="section-title"><u>PASAL 1</u> <br> KETENTUAN POKOK AKAD</div>
                <div class="section-content">
                    Ketentuan-ketentuan pokok Akad ini meliputi sebagai berikut : <br>
                    
                    <table>
                        <tr>
                            <td style="width: 260px;">a. Sisa Piutang (pokok+margin)</td>
                            <td>:</td>
                            <td>Rp. {{ number_format($item->pokok_pembiayaan + $item->margin_pembiayaan) }} ( {{ terbilang($item->pokok_pembiayaan + $item->margin_pembiayaan) }} )</td>
                        </tr>
                        <tr>
                            <td style="width: 260px;">b. Angsuran</td>
                            <td>:</td>
                            <td>Rp. {{ number_format($item->angsuran_pembiayaan) }} per minggu</td>
                        </tr>
                        <tr>
                            <td style="width: 260px;">c. Jangka Waktu Pembiayaan</td>
                            <td>:</td>
                            <td>{{ $item->tenor_pembiayaan }} Minggu</td>
                        </tr>
                        <tr>
                            <td style="width: 260px;">d. Jatuh Tempo Pembiayaan</td>
                            <td>:</td>
                            <td>Tanggal {{ $jatuh_tempo }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="section">
                <div class="section-title"><u>PASAL 2</u> <br> PERUBAHAN KETENTUAN POKOK AKAD</div>
                <div class="section-content">
                    PIHAK KEDUA mengajukan perubahan ketentuan pokok akad sebagai berikut : <br>
                    <table>
                        <tr>
                            <td style="width: 260px;">a. Sisa Piutang (pokok+margin)</td>
                            <td>:</td>
                            <td>Rp. {{ number_format($item->rest_pokok + $item->rest_margin) }} ( {{ terbilang($item->rest_pokok + $item->rest_margin) }} )</td>
                        </tr>
                        <tr>
                            <td style="width: 260px;">b. Angsuran</td>
                            <td>:</td>
                            <td>Rp. {{ number_format($item->rest_angsuran) }} per minggu</td>
                        </tr>
                        <tr>
                            <td style="width: 260px;">c. Jangka Waktu Pembiayaan</td>
                            <td>:</td>
                            <td>{{ $item->rest_tenor }} Minggu</td>
                        </tr>
                        <tr>
                            <td style="width: 260px;">d. Jatuh Tempo Pembiayaan</td>
                            <td>:</td>
                            <td>Tanggal {{ $rest_jatuh_tempo }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="section">
                <div class="section-title"><u>PASAL 3</u> <br> KETENTUAN TAMBAHAN</div>
                <div class="section-content">
                    1. Apabila dikemudian hari PIHAK KEDUA bermaksud untuk mempercepat jangka waktu pembiayaan
                    dengan menambah besarnya angsuran dengan tidak merubah besarnya sisa piutang sesaui kesepakatan
                    Para Pihak maka atas perubahan terebut akan dibuatkan surat pemberitahuan yang akan menjadu satu
                    kesatuan dengan Addendum ini
                    2. Hal-hal lain yang belum cukup diatur dalam Perjanjian ini, akan diatur berdasarkan kesepakatan para
                    pihak kedalam surat/akad yang merupakan satu kesatuan dengan perjanjian ini
                    Demikian Perjanjian ini dibuat dan ditanda tangani di {{ $item->kota }} pada hari {{ $hari }} tanggal {{ $tanggal }}
                </div>
            </div>
            <div class="section">
                <div class="section-content">
                    <table style="width: 100%; margin-top: 40px;">
                        <tr>
                            <td>
                                <p>PIHAK PERTAMA</p><br><br>
                                <p>(KATRINE TIARA DEWI SEPTIANA)<br>Marketing Manager</p>
                            </td>
                            <td>
                                <p>PIHAK KEDUA</p><br><br>
                                <p>( {{ $item->nama_anggota }} )<br></p>
                            </td>
                        </tr>
                    </table>
                    <p style="text-align: center">Saksi</p>
                    <table style="width: 100%; margin-top: 40px;">
                        <tr>
                            <td>
                                <p></p><br>
                                <p>( {{ $item->nama_ao }} )<br>Ketua Kelompok</p>
                            </td>
                            <td>
                                <p></p><br>
                                <p>( {{ $item->waris }} )<br>Suami/Penjamin</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
@endforeach