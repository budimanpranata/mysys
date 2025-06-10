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
    </style>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="code_kel" class="col-sm-2 col-form-label">Kode Kelompok</label>
                        <div class="col-sm-6">
                            <select class="form-control select2-ajax" id="code_kel" style="width: 100%;">
                                <!-- Opsi akan di-load via AJAX -->
                            </select>
                        </div>
                    </div>
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
                                    <th>UNIT</th>
                                    <th>KELOMPOK</th>
                                    <th>NOA</th>
                                    <th>NAMA AO</th>
                                    <th>SETORAN</th>
                                    <th>TGL</th>
                                    <th>AKSI</th>
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
            // Inisialisasi Select2 dengan AJAX
            $('.select2-ajax').select2({
                placeholder: 'Cari Kode Kelompok...',
                // minimumInputLength: 3, // Minimal karakter untuk mulai pencarian
                ajax: {
                    url: "{{ route('cari.kelompok') }}", // Route untuk cari kelompok
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
                                    id: item.code_kel,
                                    text: item.code_kel + ' - ' + item.nama_kel
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });

        $(document).ready(function () {
            $('#filterButton').click(function () {
                // Ambil nilai input
                var code_kel = $('#code_kel').val();

                // Validasi input
                if (code_kel === '') {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Kode Kelompok harus diisi!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Tampilkan loading sebelum request AJAX
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
                    url: "{{ route('setoranPerkelompok.filter') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        code_kel: code_kel,
                    },
                    success: function (response) {
                        Swal.close(); // Tutup loading
                        var tbody = $('table tbody');
                        tbody.empty(); // Bersihkan tabel

                        // Format angka dengan separator
                        function formatNumber(num) {
                            return new Intl.NumberFormat('id-ID').format(num);
                        }

                        if (response.data) {
                            // Fungsi untuk handle proses
                            function proses(code_kel) {
                                Swal.fire({
                                    title: 'Konfirmasi Proses',
                                    text: `Anda yakin ingin memproses kelompok ${code_kel}?`,
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Ya, Proses',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Tampilkan loading
                                        Swal.fire({
                                            title: 'Memproses...',
                                            allowOutsideClick: false,
                                            didOpen: () => Swal.showLoading()
                                        });

                                        // AJAX untuk proses
                                        $.ajax({
                                            url: "{{ url('/transaksi/setoran-perkelompok/proses') }}/" + code_kel,
                                            method: "POST",
                                            data: {
                                                _token: "{{ csrf_token() }}"
                                            },
                                            success: function(response) {
                                                Swal.fire({
                                                    title: 'Berhasil!',
                                                    text: response.message || 'Proses berhasil dilakukan',
                                                    icon: 'success',
                                                    confirmButtonText: 'OK'
                                                });
                                                // Refresh data jika perlu
                                                $('#filterButton').click();
                                            },
                                            error: function(xhr) {
                                                Swal.fire({
                                                    title: 'Gagal!',
                                                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses',
                                                    icon: 'error',
                                                    confirmButtonText: 'OK'
                                                });
                                            }
                                        });
                                    }
                                });
                            }

                            $(document).on('click', '.btn-proses', function() {
                                const code_kel = $(this).data('code-kel');
                                proses(code_kel);
                            });

                            // Tampilkan data kelompok
                            tbody.append(`
                                <tr>
                                    <td>${response.data.code_unit || '-'}</td>
                                    <td>${response.data.nama_kel || '-'}</td>
                                    <td>${response.jumlah_anggota || '-'}</td>
                                    <td>${response.data.cao || '-'}</td>
                                    <td>${formatNumber(response.setoran) || '-'}</td>
                                    <td>${response.tgl || '-'}</td>
                                    <td>
                                        <button class="btn-proses btn btn-primary btn-sm" 
                                            data-code-kel="${response.data.code_kel}">
                                            Proses
                                        </button>
                                    </td>
                                </tr>
                            `);

                            // SweetAlert jika data ditemukan
                            Swal.fire({
                                title: 'Data Ditemukan!',
                                text: 'Data kelompok berhasil dimuat.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });

                        } else {
                            Swal.fire({
                                title: 'Data Tidak Ditemukan!',
                                text: 'Tidak ada data kelompok yang cocok dengan pencarian Anda.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            tbody.append('<tr><td colspan="5" class="text-center">Data tidak ditemukan</td></tr>');
                        }
                    },
                    error: function (xhr) {
                        Swal.close(); // Tutup loading
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
        });
    </script>
@endpush