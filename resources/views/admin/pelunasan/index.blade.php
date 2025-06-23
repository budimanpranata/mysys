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
                    <div class="form-group row">
                        <label for="norek" class="col-sm-2 col-form-label">Cari Anggota</label>
                        <div class="col-sm-6">
                            <select class="form-control select2-ajax" id="norek" style="width: 100%;">
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
                    <div id="result" class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Norek</th>
                                    <th>Nama</th>
                                    <th>Plafond</th>
                                    <th>Angsuran</th>
                                    <th>Tgl Jatuh Tempo</th>
                                    <th>Saldo OS</th>
                                    <th>Saldo Rek</th>
                                    <th>Unit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan dimuat di sini -->
                            </tbody>
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
                placeholder: 'Cari Anggota...',
                // minimumInputLength: 3, // Minimal karakter untuk mulai pencarian
                ajax: {
                    url: "{{ route('pelunasan.cariAnggota') }}",
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
                                    id: item.cif,
                                    text: item.cif + ' - ' + item.nama
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $('#filterButton').on('click', function() {
                const cif = $('#norek').val();

                if (!cif) {
                    swal("Peringatan", "Silakan pilih anggota terlebih dahulu.", "warning");
                    return;
                }

                // Tampilkan loading dengan SweetAlert
                Swal.fire({
                    title: 'Memuat Data',
                    html: 'Sedang mengambil data anggota...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('pelunasan.getAnggota') }}",
                    type: 'GET',
                    data: {
                        cif: cif
                    },
                    success: function(response) {
                        const tbody = $('table tbody');
                        tbody.empty(); // kosongkan isi sebelumnya

                        // Tutup SweetAlert loading
                        Swal.close();

                        if (response.length === 0) {
                            tbody.append('<tr><td colspan="9" class="text-center">Tidak ada data ditemukan</td></tr>');
                            swal("Info", "Tidak ada data ditemukan", "info");
                            return;
                        }

                        $.each(response, function(index, item) {
                            tbody.append(`
                                <tr>
                                    <td>${item.norek}</td>
                                    <td>${item.nama}</td>
                                    <td>${item.plafond}</td>
                                    <td>${item.angsuran}</td>
                                    <td>${item.tgl_jatuh_tempo}</td>
                                    <td>${item.os}</td>
                                    <td>${item.saldo_rekening}</td>
                                    <td>${item.unit}</td>
                                    <td>
                                        <button id="proses" class="btn btn-sm btn-primary">
                                            <i class="fas fa-check-circle"></i> Proses
                                        </button>
                                    </td>

                                </tr>
                            `);
                        });
                    },
                    error: function() {
                        // Tutup SweetAlert loading dan tampilkan pesan error
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal mengambil data anggota'
                        });
                    }
                });
            });

            // Fungsi untuk proses pelunasan
            $(document).on('click', '#proses', function() {
                const row = $(this).closest('tr');
                const norek = row.find('td:eq(0)').text();
                const nama = row.find('td:eq(1)').text();
                
                Swal.fire({
                    title: 'Konfirmasi Pelunasan',
                    html: `Apakah Anda yakin ingin memproses pelunasan untuk:<br>
                        <b>No. Rekening:</b> ${norek}<br>
                        <b>Nama:</b> ${nama}<br>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Proses!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading
                        Swal.fire({
                            title: 'Memproses...',
                            html: 'Sedang memproses pelunasan',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // AJAX untuk proses pelunasan
                        $.ajax({
                            url: "{{ route('pelunasan.proses') }}",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                norek: norek
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message || 'Pelunasan berhasil diproses'
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                let errorMessage = 'Terjadi kesalahan saat memproses';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: errorMessage
                                });
                            }
                        });
                    }
                });
            });
        });

    </script>
@endpush