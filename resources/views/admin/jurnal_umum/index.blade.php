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
                            <label for="kode_rekening" class="col-sm-2 col-form-label">Rekening</label>
                            <div class="col-sm-6">
                                <select class="form-control select2-ajax" name="kode_rekening" id="coa" style="width: 100%;">
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="posisi" class="col-sm-2 col-form-label">Posisi</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="posisi" id="posisi" style="width: 100%;">
                                    <option value="debet">Debet</option>
                                    <option value="kredit">Kredit</option>
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
                            <label for="jumlah" class="col-sm-2 col-form-label">Jumlah (Rp)</label>
                            <div class="col-sm-6">
                                <input type="number" name="jumlah" class="form-control" id="jumlah">
                                <small id="preview-jumlah" class="form-text text-muted mt-1">
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
                    <span id="balance-error" class="text-danger font-weight-bold d-none">
    Total Debet dan Kredit tidak balance!
</span>
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
                    <button type="button" class="btn btn-secondary" id="btn-cetak-pdf">
                        Cetak PDF
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
                    url: "{{ route('jurnalUmum.getCoa') }}",
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

            $('#jumlah').on('input', function () {
                const value = parseFloat($(this).val());
                if (!isNaN(value)) {
                    $('#preview-jumlah').text(formatRupiah(value));
                } else {
                    $('#preview-jumlah').text('Rp 0');
                }
            });


            // Ketika tombol Input ditekan
            let jumlah = 0;

            function updateJumlah() {
                let totalDebet = 0;
                let totalKredit = 0;

                $('#data-transaksi-body tr').each(function () {
                    const debet = parseFloat($(this).find('.debet').text().replace(/,/g, '')) || 0;
                    const kredit = parseFloat($(this).find('.kredit').text().replace(/,/g, '')) || 0;
                    totalDebet += debet;
                    totalKredit += kredit;
                });

                $('#total-debet').text(totalDebet.toLocaleString());
                $('#total-kredit').text(totalKredit.toLocaleString());

                showBalanceError(!isBalanced());
            }

            function isBalanced() {
                const totalDebet = parseFloat($('#total-debet').text().replace(/,/g, '')) || 0;
                const totalKredit = parseFloat($('#total-kredit').text().replace(/,/g, '')) || 0;
                return totalDebet === totalKredit;
            }

            function showBalanceError(show) {
                if (show) {
                    $('#balance-error').removeClass('d-none');
                } else {
                    $('#balance-error').addClass('d-none');
                }
            }

            $('#btn-tambah').click(function () {
                let noTransaksi = $('#no_transaksi').val();
                let tanggal = $('#tanggal_transaksi').val();
                let keteranganTransaksi = $('#keterangan_transaksi').val();
                let kodeRekening = $('#coa').val();
                let posisi = $('#posisi').val();
                let jumlah = parseFloat($('#jumlah').val());

                if (!tanggal || !kodeRekening || !jumlah || isNaN(jumlah)) {
                    alert('Mohon lengkapi data terlebih dahulu.');
                    return;
                }

                // Hitung nilai debet dan kredit berdasarkan posisi
                let debet = posisi === 'debet' ? jumlah : 0;
                let kredit = posisi === 'kredit' ? jumlah : 0;

                let row = `
                    <tr data-posisi="${posisi}">
                        <td>${kodeRekening}</td>
                        <td>${keteranganTransaksi}</td>
                        <td>${tanggal}</td>
                        <td class="debet">${debet.toLocaleString()}</td>
                        <td class="kredit">${kredit.toLocaleString()}</td>
                        <td><button class="btn btn-danger btn-sm btn-hapus">Hapus</button></td>
                    </tr>
                `;



                $('#data-transaksi-body').append(row);

                // Reset form input
                $('#coa').val(null).trigger('change');
                $('#keterangan_transaksi').val('');
                $('#jumlah').val('');
                $('#preview-jumlah').text('Rp 0');

                updateJumlah();
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
                        updateJumlah();
                        Swal.fire('Dihapus!', 'Data berhasil dihapus.', 'success');
                    }
                });
            });

            // fungsi simpan
            $('#btn-simpan').click(function () {
                let data = [];

                $('#data-transaksi-body tr').each(function () {
                    let row = $(this);
                    let kode_rekening = row.find('td:eq(0)').text().trim();
                    let keterangan_transaksi = row.find('td:eq(1)').text().trim();
                    let tanggal_transaksi = row.find('td:eq(2)').text().trim();
                    let debet = parseFloat(row.find('td:eq(3)').text().replace(/,/g, '')) || 0;
                    let kredit = parseFloat(row.find('td:eq(4)').text().replace(/,/g, '')) || 0;
                    let posisi = row.data('posisi');

                    data.push({
                        kode_transaksi: $('#kode_transaksi').val(),
                        tanggal_transaksi: tanggal_transaksi,
                        kode_rekening: kode_rekening,
                        keterangan_transaksi: keterangan_transaksi,
                        jumlah: posisi === 'debet' ? debet : kredit,
                        posisi: posisi
                    });
                });

                if (data.length === 0) {
                    Swal.fire('Oops!', 'Tidak ada data untuk disimpan.', 'warning');
                    return;
                }

                $.ajax({
                    url: "{{ route('jurnalUmum.simpan') }}",
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

            // fungsi cetak
            $('#btn-cetak-pdf').click(function () {
                let data = [];

                $('#data-transaksi-body tr').each(function () {
                    let row = $(this);
                    let kode_rekening = row.find('td:eq(0)').text().trim();
                    let keterangan_transaksi = row.find('td:eq(1)').text().trim();
                    let tanggal_transaksi = row.find('td:eq(2)').text().trim();
                    let debet = parseFloat(row.find('td:eq(3)').text().replace(/,/g, '')) || 0;
                    let kredit = parseFloat(row.find('td:eq(4)').text().replace(/,/g, '')) || 0;
                    let posisi = row.data('posisi');

                    data.push({
                        kode_transaksi: $('#kode_transaksi').val(),
                        tanggal_transaksi: tanggal_transaksi,
                        kode_rekening: kode_rekening,
                        keterangan_transaksi: keterangan_transaksi,
                        jumlah: posisi === 'debet' ? debet : kredit,
                        posisi: posisi
                    });
                });

                if (data.length === 0) {
                    Swal.fire('Oops!', 'Tidak ada data untuk dicetak.', 'warning');
                    return;
                }

                // Kirim ke Laravel untuk generate PDF
                $.ajax({
                    url: "{{ route('jurnalUmum.cetak') }}",
                    method: "POST",
                    xhrFields: {
                        responseType: 'blob' // Agar bisa buka PDF
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    data: { transaksi: data },
                    success: function (response) {
                        let blob = new Blob([response], { type: 'application/pdf' });
                        let link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.target = '_blank';
                        // link.download = 'transaksi-jurnal.pdf';
                        link.click();
                    },
                    error: function () {
                        Swal.fire('Gagal!', 'Gagal mencetak PDF.', 'error');
                    }
                });
            });

        });

    </script>
@endpush
