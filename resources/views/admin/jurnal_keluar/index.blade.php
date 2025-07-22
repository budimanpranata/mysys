@extends('layouts.main')

@section('content-header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ $title }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
            border: 1px solid #ced4da;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .anggota-check {
            transform: scale(1.2);
        }
        #proses-terpilih {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        .text-right {
            text-align: right;
        }
        .bg-light {
            background-color: #f8f9fa !important;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <form id="transactionForm" method="POST" action="">
                        @csrf

                        <div class="form-group row">
                            <label for="kode_transaksi" class="col-sm-2 col-form-label">No Transaksi</label>
                            <div class="col-sm-6">
                                <input type="text" name="kode_transaksi" class="form-control" id="kode_transaksi"
                                    value="{{ $kodeTransaksi }}" readonly>
                                <div id="" class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tanggal_transaksi" class="col-sm-2 col-form-label">Tanggal</label>
                            <div class="col-sm-6">
                                <input type="date" class="form-control" value="{{ $paramTanggal }}" id="tanggal_transaksi">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="keterangan_transaksi" class="col-sm-2 col-form-label">Keterangan Jurnal</label>
                            <div class="col-sm-6">
                                <input name="keterangan_transaksi" name="keterangan_transaksi" class="form-control" id="keterangan_transaksi">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kode_rekening" class="col-sm-2 col-form-label">Rekening Tujuan</label>
                            <div class="col-sm-6">
                                <select class="form-control select2-ajax" name="kode_rekening" id="coa" style="width: 100%;">
                                    <option>-- Plih Rekening Tujuan --</option>
                                    <option value="1311000">BSI</option>
                                    <option value="1332000">Mandiri Pedurungan</option>
                                    <option value="1333000">BRI</option>
                                    <option value="1335000">Mandiri</option>
                                    <option value="1963000">Selisih Kas Kurang</option>
                                    <option value="2472000">Selisih Kas Lebih</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="jenis_transaksi" class="col-sm-2 col-form-label">Jenis Transaksi</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="jenis_transaksi" id="jenis_transaksi" style="width: 100%;">
                                    <option>-- Plih Jenis Transaksi --</option>
                                    <option value="1010">Setor ke KP</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="jumlah" class="col-sm-2 col-form-label">Jumlah (Rp)</label>
                            <div class="col-sm-6">
                                <input type="number" name="jumlah" class="form-control" id="jumlah">
                                <span id="preview-jumlah" class="form-text text-muted mt-1">
                                    Rp 0
                                </span>
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-sm-6 offset-sm-2">
                                <button type="button" class="btn btn-primary" id="btn-tambah">
                                    Input
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div id="result">
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>KODE REKENING</th>
                                    <th>KETERANGAN</th>
                                    <th>TGL TRANSAKSI</th>
                                    <th>DEBET</th>
                                    <th>KREDIT</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody id="data-transaksi-body"></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">TOTAL</th>
                                    <th id="total-debet">0</th>
                                    <th id="total-kredit">0</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                    <button type="submit" class="btn btn-primary" id="btn-simpan">
                        Simpan
                    </button>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        // const rekeningUnitLogin = "{{ auth()->user()->unit }}";
        const rekeningUnitLogin = "{{ $kodeGL }}";

        let transaksiList = [];

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(angka);
        }

        $('#jumlah').on('input', function () {
            const value = parseFloat($(this).val());
            if (!isNaN(value)) {
                $('#preview-jumlah').text(formatRupiah(value));
            } else {
                $('#preview-jumlah').text('Rp 0');
            }
        });

        function updateTabelTransaksi() {
            let totalDebet = 0;
            let totalKredit = 0;

            $('#data-transaksi-body').empty();

            transaksiList.forEach((item, index) => {
                const row = `
                    <tr>
                        <td>${item.kode_rekening}</td>
                        <td>${item.keterangan_transaksi}</td>
                        <td>${item.tanggal_transaksi}</td>
                        <td>${item.jenis === 'debet' ? formatRupiah(item.jumlah) : '-'}</td>
                        <td>${item.jenis === 'kredit' ? formatRupiah(item.jumlah) : '-'}</td>
                        <td><button type="button" class="btn btn-danger btn-sm btn-hapus" data-index="${index}">Hapus</button></td>
                    </tr>
                `;

                $('#data-transaksi-body').append(row);

                if (item.jenis === 'debet') totalDebet += item.jumlah;
                else if (item.jenis === 'kredit') totalKredit += item.jumlah;

            });

            $('#total-debet').text(formatRupiah(totalDebet));
            $('#total-kredit').text(formatRupiah(totalKredit));
        }

        $('#btn-tambah').on('click', function () {
            let kodeRekeningTujuan = $('#coa').val();
            let keterangan = $('#keterangan_transaksi').val();
            let tanggal = $('#tanggal_transaksi').val();
            let jenis_transaksi = $('#jenis_transaksi').val();
            let jumlah = parseFloat($('#jumlah').val());

            if (!kodeRekeningTujuan || !keterangan || !tanggal || !jenis_transaksi || isNaN(jumlah)) {
                alert('Lengkapi semua data terlebih dahulu.');
                return;
            }

            // Default kode rekening (tanpa prefix)
            let kodeRekeningDebet = kodeRekeningTujuan;
            let kodeRekeningKredit = rekeningUnitLogin;

            // Jika jenis transaksi adalah 'Setor ke KP', tambahkan prefix '1010-'
            if (jenis_transaksi === '1010') {
                kodeRekeningDebet = '1010-' + kodeRekeningTujuan;
                kodeRekeningKredit = '1010-' + rekeningUnitLogin;
            }

            // Bersihkan dulu (reset)
            let entries = [];

            if (jenis_transaksi === 'lainnya') {
                // Hanya push 1 baris debet (nanti lawannya di backend)
                entries.push({
                    kode_transaksi: $('#kode_transaksi').val(),
                    kode_rekening: kodeRekeningDebet,
                    tanggal_transaksi: tanggal,
                    keterangan_transaksi: keterangan,
                    jumlah: jumlah,
                    jenis: 'debet',
                    jenis_transaksi: $('#jenis_transaksi').val()
                });
            } else {
                // Push dua baris debet-kredit
                entries.push({
                    kode_transaksi: $('#kode_transaksi').val(),
                    kode_rekening: kodeRekeningDebet,
                    tanggal_transaksi: tanggal,
                    keterangan_transaksi: keterangan,
                    jumlah: jumlah,
                    jenis: 'debet',
                    jenis_transaksi: $('#jenis_transaksi').val()
                });

                entries.push({
                    kode_transaksi: $('#kode_transaksi').val(),
                    kode_rekening: kodeRekeningKredit,
                    tanggal_transaksi: tanggal,
                    keterangan_transaksi: keterangan,
                    jumlah: jumlah,
                    jenis: 'kredit',
                    jenis_transaksi: $('#jenis_transaksi').val()
                });

                // Optional: akun penampung
                entries.push({
                    kode_transaksi: $('#kode_transaksi').val(),
                    kode_rekening: '2500000',
                    tanggal_transaksi: tanggal,
                    keterangan_transaksi: keterangan,
                    jumlah: jumlah,
                    jenis: 'debet',
                    jenis_transaksi: $('#jenis_transaksi').val()
                });
            }

            // Masukkan semua entry ke transaksiList
            transaksiList.push(...entries);
            updateTabelTransaksi();

            // Reset input
            $('#jumlah').val('');
            $('#keterangan_transaksi').val('');
            $('#jenis_transaksi').val('');
            $('#coa').val('').trigger('change');
        });

        $('#data-transaksi-body').on('click', '.btn-hapus', function() {
            const index = $(this).data('index');
            transaksiList.splice(index, 1);
            updateTabelTransaksi();
        });

        $('#btn-simpan').on('click', function () {
            if (transaksiList.length === 0) {
                Swal.fire('Gagal!', 'Belum ada data yang ditambahkan.', 'error');
                return;
            }

            $.ajax({
                url: "{{ route('jurnalKeluar.store') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    transaksi: transaksiList
                },
                success: function (response) {
                    Swal.fire('Berhasil!', 'Data berhasil disimpan.', 'success').then(() => {
                        window.location.reload();
                    });
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan data.', 'error');
                }
            });
        });



    });
</script>
@endpush
