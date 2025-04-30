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
                    <!-- Input Kode Kelompok -->
                    <div class="form-group row">
                        <label for="code_kel" class="col-sm-2 col-form-label">Kode Kelompok</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="code_kel" placeholder="Masukkan Kode Kelompok">
                        </div>
                    </div>

                    <!-- Tombol Cari -->
                    <div class="form-group row">
                        <div class="col-sm-6 offset-sm-2">
                            <button id="filterButton" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabel Hasil Pencarian -->
            <div class="card">
                <div class="card-body">
                    <div id="result">
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>PILIH</th>
                                    <th>NO</th>
                                    <th>CIF</th>
                                    <th>NAMA</th>
                                    <th>UNIT</th>
                                    <th>PEMBIAYAAN</th>
                                    <th>MARGIN</th>
                                    <th>SETOTAN</th>
                                    <th>ANGSURAN</th>
                                    <th>TWM</th>
                                    <th>SALDO TWM</th>
                                    <th>SISA TWM</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot id="total-row" style="display: none;">
                                <tr>
                                    <th colspan="5" class="text-right">TOTAL</th>
                                    <th class="text-right" id="total-pembiayaan">0</th>
                                    <th class="text-right" id="total-margin">0</th>
                                    <th class="text-right" id="total-setoran">0</th>
                                    <th class="text-right" id="total-angsuran">0</th>
                                    <th class="text-right" id="total-twm">0</th>
                                    <th class="text-right" id="total-saldo-twm">0</th>
                                    <th class="text-right" id="total-sisa-twm">0</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- Tombol Cetak PDF -->
                    <div class="mt-3 text-right">
                        <button id="realisasi" class="btn btn-success">
                            <i class="fas fa-print"></i> Realisasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#filterButton').click(function() {
                var code_kel = $('#code_kel').val();

                if (code_kel === '') {
                    Swal.fire({
                            title: 'Peringatan!',
                            text: 'Kode Kelompok harus diisi!',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    return;
                }

                // tampilkan loading sebelum request AJAX
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Silakan tunggu',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // AJAX request
                $.ajax({
                    url: "{{ route('realisasi.tagihanKelompok.getKelompok') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        code_kel: code_kel,
                    },
                    success: function (response) {
                        Swal.close();
                        var tbody = $('table tbody');
                        tbody.empty();

                        // inisialisasi total
                        var totalPembiayaan = 0;
                        var totalMargin = 0;
                        var totalSetoran = 0;
                        var totalAngsuran = 0;
                        var totalTwm = 0;
                        var totalSaldoTwm = 0;
                        var totalSisaTwm = 0;

                        if (response.data.length > 0) {
                            // loop hasil pencarian
                            $.each(response.data, function (index, item) {
                                // konversi ke number dan hitung total
                                var pembiayaan = parseFloat(item.os) || 0;
                                var margin = parseFloat(item.saldo_margin) || 0;
                                var setoran = parseFloat(item.bulat) || 0;
                                var angsuran = parseFloat(item.angsuran) || 0;
                                var twm = parseFloat(item.twm) || 0;
                                var saldoTwm = parseFloat(item.saldo_twm) || 0;
                                var sisaTwm = parseFloat(item.sisa_twm) || 0;
                                
                                totalPembiayaan += pembiayaan;
                                totalMargin += margin;
                                totalSetoran += setoran;
                                totalAngsuran += angsuran;
                                totalTwm += twm;
                                totalSaldoTwm += saldoTwm;
                                totalSisaTwm += sisaTwm;

                                tbody.append(`
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="realisasi-checkbox" 
                                                data-cif="${item.cif}">
                                        </td>
                                        <td>${index + 1}</td>
                                        <td>${item.cif}</td>
                                        <td>${item.nama_anggota}</td>
                                        <td>${item.unit}</td>
                                        <td class="text-right">${formatNumber(pembiayaan)}</td>
                                        <td class="text-right">${formatNumber(margin)}</td>
                                        <td class="text-right">${formatNumber(setoran)}</td>
                                        <td class="text-right">${formatNumber(angsuran)}</td>
                                        <td class="text-right">${formatNumber(twm)}</td>
                                        <td class="text-right">${formatNumber(saldoTwm)}</td>
                                        <td class="text-right">${formatNumber(sisaTwm)}</td>
                                    </tr>
                                `);
                            });

                            // update total footer
                            $('#total-pembiayaan').text(formatNumber(totalPembiayaan));
                            $('#total-margin').text(formatNumber(totalMargin));
                            $('#total-setoran').text(formatNumber(totalSetoran));
                            $('#total-angsuran').text(formatNumber(totalAngsuran));
                            $('#total-twm').text(formatNumber(totalTwm));
                            $('#total-saldo-twm').text(formatNumber(totalSaldoTwm));
                            $('#total-sisa-twm').text(formatNumber(totalSisaTwm));
                            
                            // tampilkan footer total
                            $('#total-row').show();

                            // SweetAlert jika data ditemukan
                            Swal.fire({
                                title: 'Data Ditemukan!',
                                text: response.data.length + ' data berhasil dimuat.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });

                        } else {
                            Swal.fire({
                                title: 'Data Tidak Ditemukan!',
                                text: 'Tidak ada data yang cocok dengan pencarian Anda.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            tbody.append('<tr><td colspan="12" class="text-center text-danger">Data tidak ditemukan</td></tr>');
                            // sembunyikan footer total jika tidak ada data
                            $('#total-row').hide();
                        }
                    },
                    error: function (xhr) {
                        Swal.close();
                            Swal.fire({
                                title: 'Terjadi Kesalahan!',
                                text: 'Gagal mengambil data, silakan coba lagi.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        console.error(xhr.responseText);
                    }
                });
            });

            // fungsi untuk format number dengan separator ribuan
            function formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }

            // fungsi ketika checkbox diubah
            $(document).on('change', '.realisasi-checkbox', function() {
                const baris = $(this).closest('tr');
                
                if ($(this).is(':checked')) {
                    $(this).data({
                        'cif': $(this).data('cif'),
                    });
                } else {
                    $(this).removeData(['cif']);
                }
            });

            // ini fungsi simpan realisasi
            $('#realisasi').click(function() {
                const dataDipilih = [];
                
                $('.realisasi-checkbox:checked').each(function() {
                    dataDipilih.push({
                        cif: $(this).data('cif'),
                    });
                });

                if (dataDipilih.length === 0) {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Silakan pilih minimal 1 anggota untuk direalisasi!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Realisasi',
                    text: `Anda akan melakukan realisasi untuk ${dataDipilih.length} anggota. Lanjutkan?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Proses',
                    cancelButtonText: 'Batal'
                }).then((hasil) => {
                    if (hasil.isConfirmed) {
                        prosesRealisasi(dataDipilih);
                    }
                });
            });

            // fungsi proses realisasi ke controoler
            function prosesRealisasi(data) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang menyimpan data',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('realisasi.tagihanKelompok.process') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        items: data
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Sukses!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#filterButton').click();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Gagal!',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    </script>
    
@endpush