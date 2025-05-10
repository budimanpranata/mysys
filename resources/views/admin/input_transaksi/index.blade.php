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
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <form id="transactionForm" method="POST" action="">
                        @csrf

                        <div class="form-group row">
                            <label for="cif" class="col-sm-2 col-form-label">CIF</label>
                            <div class="col-sm-6">
                                <input type="text" name="cif" class="form-control" id="cif"
                                    placeholder="Masukkan CIF" required>
                                <div id="cifError" class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="nama" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nominal" class="col-sm-2 col-form-label">Nominal</label>
                            <div class="col-sm-6">
                                <input type="number" name="nominal" class="form-control" id="nominal"
                                    placeholder="Masukkan Nominal" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                            <div class="col-sm-6">
                                <textarea name="keterangan" class="form-control" id="keterangan" cols="30" rows="3"
                                    placeholder="Masukan Keterangan"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="jenis_transaksi" class="col-sm-2 col-form-label">Jenis Transaksi</label>
                            <div class="col-sm-6">
                                <select name="jenis_transaksi" class="form-control" id="jenis_transaksi" required>
                                    <option hidden value="">-- Pilih Jenis Transaksi --</option>
                                    <option value="1">Simpanan Tunai Pokok Wajib</option>
                                    <option value="2">Penarikan Tunai</option>
                                    <option value="3">Setoran Angsuran</option>
                                    <option value="4">Pemindahbukuan</option>
                                    <option value="5">Setoran Angsuran WO</option>
                                </select>
                            </div>
                        </div>

                        <!-- Form tambahan untuk pemindahbukuan -->
                        <div id="pemindahbukuanFields" style="display:none;">
                            <div class="form-group row">
                                <label for="jenis_pemindahan" class="col-sm-2 col-form-label">Jenis Pemindahan</label>
                                <div class="col-sm-6">
                                    <select name="jenis_pemindahan" class="form-control" id="jenis_pemindahan">
                                        <option value="debet">Debet</option>
                                        <option value="kredit">Kredit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="jenis_simpanan" class="col-sm-2 col-form-label">Jenis Simpanan</label>
                                <div class="col-sm-6">
                                    <select name="jenis_simpanan" class="form-control" id="jenis_simpanan">
                                        <option value="pokok">Pokok</option>
                                        <option value="wajib">Wajib</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6 offset-sm-2">
                                <button type="submit" class="btn btn-primary">
                                    Input
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Hasil Pencarian -->
            <div class="card">
                <div class="card-body">
                    <div id="result">
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>NO REKENING</th>
                                    <th>NAMA</th>
                                    <th>TGL TRANSAKSI</th>
                                    <th>DEBET</th>
                                    <th>KREDIT</th>
                                    <th>SALDO</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Fungsi untuk mengambil data anggota berdasarkan CIF
            $('#cif').on('blur', function() {
                let cif = $(this).val().trim();

                if (cif) {
                    $.ajax({
                        url: '/transaksi/input-transaksi/get-cif/' + cif,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#nama').val(response.data.nama);
                                $('#cif').removeClass('is-invalid');
                                $('#cifError').text('');
                                // Load history setelah mendapatkan CIF yang valid
                                loadTransactionHistory(cif);
                            } else {
                                $('#nama').val('');
                                $('#cif').addClass('is-invalid');
                                $('#cifError').text(response.message);
                            }
                        },
                        error: function(xhr) {
                            $('#nama').val('');
                            $('#cif').addClass('is-invalid');
                            $('#cifError').text('Nama dengan CIF tersebut tidak ditemukan.');
                        }
                    });
                } else {
                    $('#nama').val('');
                }
            });

            // Tampilkan form tambahan ketika memilih pemindahbukuan
            $('#jenis_transaksi').change(function() {
                if ($(this).val() == '4') {
                    $('#pemindahbukuanFields').show();
                } else {
                    $('#pemindahbukuanFields').hide();
                    $('#simpananFields').hide();
                }
            });

            // Form submission
            $('#transactionForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('transaksi.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // alert('Transaksi berhasil disimpan');
                            Swal.fire({
                                title: 'Transaksi berhasil disimpan!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                            // Refresh tabel transaksi
                            loadTransactionHistory($('#cif').val());
                            // Reset form
                            $('#transactionForm')[0].reset();
                            $('#nama').val('');
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = '';

                            for (let field in errors) {
                                errorMessages += errors[field][0] + '\n';
                            }

                            alert(errorMessages);
                        } else {
                            alert('Terjadi kesalahan pada server');
                        }
                    }
                });
            });

            // Fungsi untuk memuat riwayat transaksi
            function loadTransactionHistory(cif) {
                if (!cif) return;

                $.ajax({
                    url: '/transaksi/input-transaksi/history/' + cif,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        let tbody = $('#result tbody');
                        tbody.empty();

                        if (response.data && response.data.length > 0) {
                            $.each(response.data, function(index, transaksi) {
                                let row = `
                            <tr>
                                <td>${transaksi.norek || '-'}</td>
                                <td>${transaksi.nama || '-'}</td>
                                <td>${transaksi.tgl_input || '-'}</td>
                                <td>${transaksi.debet ? formatRupiah(transaksi.debet) : '0'}</td>
                                <td>${transaksi.kredit ? formatRupiah(transaksi.kredit) : '0'}</td>
                                <td>${transaksi.saldo ? formatRupiah(transaksi.saldo) : '0'}</td>
                            </tr>
                        `;
                                tbody.append(row);
                            });
                        } else {
                            tbody.append(
                                '<tr><td colspan="6" class="text-center">Tidak ada data transaksi</td></tr>'
                                );
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading transaction history:', xhr);
                    }
                });
            }

            // Fungsi untuk format mata uang Rupiah
            function formatRupiah(angka) {
                if (!angka) return '0';

                let number_string = angka.toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return 'Rp ' + rupiah;
            }
        });
    </script>
@endpush
