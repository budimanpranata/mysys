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
                                <input type="date" class="form-control" id="tanggal_transaksi">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="jenis_transaksi" class="col-sm-2 col-form-label">Keterangan Jurnal</label>
                            <div class="col-sm-6">
                                <input name="jenis_transaksi" class="form-control" id="jenis_transaksi">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kode_rekening" class="col-sm-2 col-form-label">Rekening</label>
                            <div class="col-sm-6">
                                <select class="form-control select2-ajax" name="kode_rekening" id="coa" style="width: 100%;">
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="keterangan_transaksi" class="col-sm-2 col-form-label">Keterangan Transaksi</label>
                            <div class="col-sm-6">
                                <textarea name="keterangan_transaksi" name="keterangan_transaksi" class="form-control" id="keterangan_transaksi" cols="2" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kredit" class="col-sm-2 col-form-label">Jumlah (Rp)</label>
                            <div class="col-sm-6">
                                <input type="number" name="kredit" class="form-control" id="kredit">
                                <small id="preview-kredit" class="form-text text-muted mt-1">
                                    Rp 0
                                </small>
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
                                    <th>KREDIT</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody id="data-transaksi-body">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">TOTAL</th>
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

            const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
            document.getElementById('tanggal_transaksi').value = today;
            
            // Inisialisasi Select2 dengan AJAX
            $('.select2-ajax').select2({
                placeholder: 'Cari Rekening...',
                // minimumInputLength: 3, // Minimal karakter untuk mulai pencarian
                ajax: {
                    url: "{{ route('jurnalMasuk.getCoa') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            cari: params.term // Parameter search
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.kode_rek,
                                    text: item.kode_rek + ' - ' + item.nama_rek
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            }

            $('#kredit').on('input', function () {
                const value = parseFloat($(this).val());
                if (!isNaN(value)) {
                    $('#preview-kredit').text(formatRupiah(value));
                } else {
                    $('#preview-kredit').text('Rp 0');
                }
            });


            // Ketika tombol Input ditekan
            let totalKredit = 0;

            function updateTotalKredit() {
                let total = 0;
                $('#data-transaksi-body tr').each(function () {
                    let kreditText = $(this).find('td:eq(3)').text().replace(/,/g, '');
                    total += parseFloat(kreditText) || 0;
                });
                $('#total-kredit').text(total.toLocaleString());
            }

            $('#btn-tambah').click(function () {
                let noTransaksi = $('#no_transaksi').val();
                let tanggal = $('#tanggal_transaksi').val();
                let keteranganJurnal = $('#jenis_transaksi').val();
                let keteranganTransaksi = $('#keterangan_transaksi').val();
                let kodeRekening = $('#coa').val();
                let kredit = $('#kredit').val();

                if (!tanggal || !kodeRekening || !kredit) {
                    alert('Mohon lengkapi data terlebih dahulu.');
                    return;
                }

                // Tambahkan baris baru ke tabel
                let row = `
                    <tr>
                        <td>${kodeRekening}</td>
                        <td>${keteranganTransaksi}</td>
                        <td>${tanggal}</td>
                        <td>${Number(kredit).toLocaleString()}</td>
                        <td><button class="btn btn-danger btn-sm btn-hapus">Hapus</button></td>
                    </tr>
                `;
                $('#data-transaksi-body').append(row);

                // Kosongkan input setelah ditambahkan
                $('#coa').val(null).trigger('change');
                $('#keterangan_transaksi').val('');
                $('#kredit').val('');

                updateTotalKredit();
            });

            // Delegasi event untuk tombol hapus
            $(document).on('click', '.btn-hapus', function () {
                let row = $(this).closest('tr');
                
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        row.remove();
                        updateTotalKredit();
                        Swal.fire('Dihapus!', 'Data berhasil dihapus.', 'success');
                    }
                });
            });

            // fungsi simpan
            $('#btn-simpan').click(function () {
                let data = [];

                $('#data-transaksi-body tr').each(function () {
                    data.push({
                        kode_transaksi: $('#kode_transaksi').val(),
                        tanggal_transaksi: $(this).find('td:eq(2)').text(),
                        kode_rekening: $(this).find('td:eq(0)').text(),
                        keterangan_transaksi: $(this).find('td:eq(1)').text(),
                        kredit: parseFloat($(this).find('td:eq(3)').text().replace(/,/g, '')) || 0,
                        jenis_transaksi: $('#jenis_transaksi').val()
                    });
                });

                if (data.length === 0) {
                    Swal.fire('Oops!', 'Tidak ada data untuk disimpan.', 'warning');
                    return;
                }

                $.ajax({
                    url: "{{ route('jurnalMasuk.simpan') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    data: { transaksi: data },
                    success: function (response) {
                        Swal.fire('Berhasil!', 'Data berhasil disimpan.', 'success').then(() => {
                            window.location.reload(); // atau redirect sesuai kebutuhan
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
