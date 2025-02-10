@foreach ($data as $item)
    @php
        $param_tanggal = $item->tgl_murab;
        $hari = \Carbon\Carbon::parse($param_tanggal)->locale('id')->isoFormat('dddd');
        $tanggal = \Carbon\Carbon::parse($param_tanggal)->locale('id')->isoFormat('D MMMM Y');
        $jatuh_tempo = \Carbon\Carbon::parse($param_tanggal)->addWeeks($item->tenor)->locale('id')->isoFormat('D MMMM Y'); // tambahkan 25 minggu
    @endphp

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PEMBIAYAAN AKAD MURABAHAH</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #ffffff;
            }

            .header {
                text-align: center;
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 20px;
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
                <u>PEMBIAYAAN AKAD MURABAHAH</u><br>
                NO : {{ $item->no_anggota }}
            </div>
            <hr>
            <div class="content">
                <div class="section">
                    <div class="section-content">
                        Dengan memohon petunjuk dan Ridho Allah SWT, pada Hari ini {{ $hari }} tanggal
                        {{ $tanggal }} yang bertanda tangan di bawah ini : <br><br>
                        I. Nama KATRINE TIARA DEWI SEPTIANA, jabatan Marketing Manager dalam hal ini bertindak untuk dan
                        atas nama Koperasi Simpan Pinjam dan Pembiayaan Syariah (KSPPS) Nur Insani berkedudukan di Kota
                        Tangerang Selatan berdasarkan Akta Pendirian No. 02 tanggal 7 Mei 2011 melalui notaris Nelly
                        Iralita Iswari, SH, MSI, MKn dan telah memperoleh persetujuan Kementerian Koperasi dan Usaha
                        Kecil dan Menengah No. 1024/BHM.KUM.2/XI/2011 serta perubahan-perubahannya, selanjutnya disebut
                        sebagai PIHAK PERTAMA.<br><br>
                        II. Nyonya {{ $item->nama }} berdomisili di {{ $item->anggota->alamat }}
                        {{ $item->anggota->desa }} {{ $item->anggota->kecamatan }} {{ $item->anggota->kota }}, pemegang
                        KTP dengan NIK {{ $item->anggota->ktp }} Selanjutnya disebut sebagai PIHAK KEDUA.<br><br>
                        PIHAK PERTAMA dan PIHAK KEDUA selanjutnya bersama-sama disebut "Para Pihak", sepakat untuk
                        membuat dan menandatangani Pembiayaan Akad Murabahah (selanjutnya disebut 'Akad') dengan syarat
                        dan ketentuan sebagai berikut:
                    </div>
                </div>
                <div class="section">
                    <div class="section-title"><u>PASAL 1</u> <br> KETENTUAN POKOK AKAD</div>
                    <div class="section-content">
                        Ketentuan-ketentuan pokok Akad ini meliputi sebagai berikut :<br>
                        a. Barang (Object Murabahah) : (Dijelaskan dalam Tanda Terima Barang)<br>
                        b. Harga Beli : Rp. 2,500,000 (Dua Juta Lima Ratus Ribu)<br>
                        c. Keuntungan : Rp. 512,500 (Lima Ratus Dua Belas Ribu Lima Ratus)<br>
                        d. Harga Jual/Piutang Murabahah : Rp3,012,500 (Tiga Juta Dua Belas Ribu Lima Ratus)<br>
                        e. Jumlah Angsuran Mingguan : Rp. 120,500 (Seratus Dua Puluh Ribu Lima Ratus)<br>
                        f. Hari Pembayaran Angsuran : setiap hari {{ $item->hari }}<br>
                        g. Jangka Waktu Pembiayaan : {{ $item->tenor }} Minggu<br>
                        h. Tanggal Jatuh Tempo Pembiayaan : Tanggal {{ $jatuh_tempo }}
                    </div>
                </div>
                <div class="section">
                    <div class="section-title"><u>PASAL 2</u> <br> BARANG</div>
                    <div class="section-content">
                        Barang sebagaimana dimaksud Pasal 1 huruf a. pada Akad ini dijelaskan dalam Tanda Terima Barang
                        yang menjadi satu kesatuan yang tidak terpisahkan dari Akad ini.
                    </div>
                </div>
                <div class="section">
                    <div class="section-title"><u>PASAL 3</u> <br> PELAKSANAAN PRINSIP MURABAHAH</div>
                    <div class="section-content">
                        Pelaksanaan prinsip Murabahah yang berlangsung antara PIHAK PERTAMA sebagai Penjual dengan PIHAK
                        KEDUA sebagai Pembeli dilaksanakan sebagai berikut:<br>
                        1. PIHAK KEDUA meminta kepada PIHAK PERTAMA untuk menyediakan barang.<br>
                        2. PIHAK PERTAMA menyediakan barang sesuai dengan kebutuhan PIHAK KEDUA.<br>
                        3. PIHAK PERTAMA menjual barang kepada PIHAK KEDUA sebesar harga pokok ditambah keuntungan
                        sebagaimana diterangkan pada pasal 1 Akad ini.<br>
                        4. PIHAK KEDUA membeli dengan membayar secara angsuran sebesar Harga Jual sesuai kesepakatan
                        yang diterangkan pada Pasal 1 Akad ini.
                    </div>
                </div>
                <div class="section">
                    <div class="section-title"><u>PASAL 4</u> <br> SYARAT JUAL BELI</div>
                    <div class="section-content">
                        PIHAK PERTAMA dapat merealisasikan Akad apabila PIHAK KEDUA telah memenuhi ketentuan sebagai
                        berikut :<br>
                        1. PIHAK KEDUA telah menyerahkan semua persyaratan yang diminta kepada PIHAK PERTAMA;<br>
                        2. PIHAK PERTAMA dan PIHAK KEDUA telah menandatangani perjanjian ini;<br>
                        Selanjutnya PIHAK KEDUA dengan ini mengakui dengan sebenarnya dan secara sah menerima barang
                        tersebut dan menyatakan diri secara sah berutang kepada PIHAK PERTAMA sejumlah harga jual barang
                        yang telah disepakati.
                    </div>
                </div>
                <div class="section">
                    <div class="section-title"><u>PASAL 5</u> <br> JANGKA WAKTU</div>
                    <div class="section-content">
                        Akad ini diberikan untuk jangka waktu 25 minggu, terhitung sejak tanggal {{ $tanggal }}
                        sehingga berakhir pada tanggal {{ $jatuh_tempo }}.
                    </div>
                </div>
                <div class="section">
                    <div class="section-title"><u>PASAL 6</u> <br> KUASA</div>
                    <div class="section-content">
                        PIHAK KEDUA memberi kuasa kepada PIHAK PERTAMA untuk mengurangi simpanannya yang ada pada KSPPS
                        Nur Insani untuk memenuhi seluruh kewajibannya.
                    </div>
                </div>
                <div class="section">
                    <div class="section-title"><u>PASAL 7</u> <br> PERNYATAAN</div>
                    <div class="section-content">
                        PIHAK KEDUA menyatakan dengan sukarela dan kesadaran sendiri untuk :<br>
                        1. Akan membayar angsuran tepat waktu sesuai tempat dan waktu yang telah disepakati.<br>
                        2. Tidak menggunakan pembiayaan ini untuk kepentingan pihak lain. Apabila dikemudian hari
                        terbukti digunakan pihak lain, akan tetap bertanggung jawab membayar angsuran tepat waktu.<br>
                        3. Tidak melibatkan pihak luar (LSM, Ormas atau sejenisnya) untuk tidak membayar angsuran.
                        Apabila hal tersebut tidak dipenuhi, maka PIHAK KEDUA bersedia dilaporkan kepada pihak berwajib
                        sesuai undang-undang yang berlaku.
                    </div>
                </div>
                <div class="section">
                    <div class="section-title"><u>PASAL 8</u> <br> KETENTUAN TAMBAHAN</div>
                    <div class="section-content">
                        Hal-hal lain yang belum cukup diatur dalam perjanjian ini, akan diatur berdasarkan kesepakatan
                        PARA PIHAK ke dalam surat/akta yang merupakan satu kesatuan dengan perjanjian ini. <br>
                        Demikian Perjanjian ini dibuat dan ditandatangani di KLATEN pada hari
                        {{ $hari }} tanggal {{ $tanggal }}
                    </div>
                </div>
                <div class="section">
                    <div class="section-content">
                        <table style="width: 100%; margin-top: 50px;">
                            <tr>
                                <td>
                                    <p>PIHAK PERTAMA</p><br><br>
                                    <p>(KATRINE TIARA DEWI SEPTIANA)<br>Marketing Manager</p>
                                </td>
                                <td>
                                    <p>PIHAK KEDUA</p><br><br>
                                    <p>( {{ $item->nama }} )<br></p>
                                    {{-- Ketua Kelompok --}}
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
