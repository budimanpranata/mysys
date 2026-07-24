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
                    <div class="form-group row">
                        <label for="jenis_pencarian" class="col-sm-2 col-form-label">Jenis Pencarian</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="jenis_pencarian">
                                <option value="" selected>-- pilih jenis Pencarian --</option>
                                <option value="penarikan_tabungan">Penarikan Tabungan</option>
                                <option value="deviasi_umur">Persetujuan Deviasi Umur Anggota</option>
                            </select>
                        </div>
                    </div>

                    {{-- Input Tanggal Penarikan --}}
                    <div class="form-group row" id="tanggal-penarikan-group">
                        <label class="col-sm-2 col-form-label">Tanggal Penarikan</label>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" id="tgl_penarikan">
                        </div>
                    </div>

                    {{-- Input Tanggal Pencairan --}}
                    <div class="form-group row" id="tanggal-pencairan-group">
                        <label class="col-sm-2 col-form-label">Tanggal Pencairan</label>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" id="tgl_pencairan">
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

            <!-- DATA APPROVE -->
            <div class="row mt-3" id="data-approve-section" style="display: block;">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <strong>DATA APPROVE</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered text-center mb-0">
                                <thead style="background: #f5f5f5;">
                                    <tr>
                                        <th style="width: 70px;">PILIH</th>
                                        <th>KODE CABANG</th>
                                        <th>CIF</th>
                                        <th>NAMA</th>
                                        <th>TGL TRANSAKSI</th>
                                        <th>NOREK</th>
                                        <th>NOMINAL</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody id="approveTableBody">
                                    
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-success mr-2" id="btnApproveCheckbox">
                                <i class="fas fa-check"></i> Approve
                            </button>

                            <button class="btn btn-danger" id="btnBatalCheckbox">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Sembunyikan input tanggal & tabel data di awal
            $('#tanggal-penarikan-group').hide();
            $('#tanggal-pencairan-group').hide();
            $('#data-approve-section').hide();

            // Event ketika Jenis Pencarian dipilih
            $('#jenis_pencarian').on('change', function () {
                let selectedValue = $(this).val();

                // Reset tampilan tanggal & tabel
                $('#tanggal-penarikan-group').hide();
                $('#tanggal-pencairan-group').hide();
                $('#data-approve-section').hide();
                $('#approveTableBody').html('');

                if (selectedValue === 'penarikan_tabungan') {
                    $('#tanggal-penarikan-group').show();
                } else if (selectedValue === 'deviasi_umur') {
                    $('#tanggal-pencairan-group').show();
                }
            });

            // Event ketika tombol Cari diklik
            $('#filterButton').on('click', function (e) {
                e.preventDefault();
                let jenisPencarian = $('#jenis_pencarian').val();
                let tanggalPencairan = $('#tgl_pencairan').val();
                let tanggalPenarikan = $('#tgl_penarikan').val();

                if (!jenisPencarian) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan pilih jenis pencarian terlebih dahulu!'
                    });
                    return;
                }

                // Validasi input tanggal sesuai pilihan jenis pencarian
                if (jenisPencarian === 'deviasi_umur' && !tanggalPencairan) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan pilih Tanggal Pencairan terlebih dahulu!'
                    });
                    return;
                }

                if (jenisPencarian === 'penarikan_tabungan' && !tanggalPenarikan) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan pilih Tanggal Penarikan terlebih dahulu!'
                    });
                    return;
                }

                // Tentukan URL dan Parameter berdasarkan jenis pencarian
                let targetUrl = '';
                let targetTanggal = '';

                if (jenisPencarian === 'deviasi_umur') {
                    targetUrl = "{{ route('al.deviasi.data') }}";
                    targetTanggal = tanggalPencairan;
                } else if (jenisPencarian === 'penarikan_tabungan') {
                    targetUrl = "{{ route('al.penarikan.data') }}";
                    targetTanggal = tanggalPenarikan;
                }

                // Eksekusi AJAX
                $.ajax({
                    url: targetUrl,
                    type: "GET",
                    data: {
                        tanggal: targetTanggal
                    },
                    beforeSend: function() {
                        $('#approveTableBody').html('<tr><td colspan="8">Memuat data...</td></tr>');
                        $('#data-approve-section').show();
                    },
                    success: function (response) {
                        if (response.success) {
                            if (response.html.trim() === '') {
                                $('#approveTableBody').html('<tr><td colspan="8" class="text-muted">Tidak ada data ditemukan pada tanggal tersebut.</td></tr>');
                            } else {
                                $('#approveTableBody').html(response.html);
                            }
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat memuat data.'
                        });
                        console.log(xhr.responseText);
                    }
                });
            });

            // Fungsi Tombol Approve dan Reject (Batal)
            $('#btnApproveCheckbox, #btnBatalCheckbox').on('click', function (e) {
                e.preventDefault();
                
                let actionType = $(this).attr('id') === 'btnApproveCheckbox' ? 'approve' : 'reject';
                let actionLabel = actionType === 'approve' ? 'Approve' : 'Reject';
                let jenisPencarian = $('#jenis_pencarian').val();
                let selectedIds = [];

                // Ambil semua ID dari checkbox yang dicentang
                $('input[name="selected_ids[]"]:checked').each(function () {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Pilih setidaknya satu data yang ingin diproses!'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan melakukan " + actionLabel + " pada data yang dipilih!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('al.approval.process') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                jenis_pencarian: jenisPencarian,
                                action: actionType,
                                ids: selectedIds
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    // Refresh data setelah berhasil
                                    $('#filterButton').trigger('click');
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: response.message
                                    });
                                }
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Kesalahan Server',
                                    text: 'Terjadi kesalahan pada server.'
                                });
                                console.log(xhr.responseText);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush

