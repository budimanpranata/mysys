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
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 5px 10px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="jenis_pengajuan" class="col-sm-2 col-form-label">Jenis Pengajuan</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="jenis_pengajuan">
                                <option value="" hidden>-- pilih jenis Pengajuan --</option>
                                <option value="pembiayaan">Pembiayaan</option>
                                <option value="turun_plafond">Turun Plafond</option>
                                <option value="ajukan_kembali">Ajukan Kembali</option>
                                <option value="hapus_pengajuan" selected>Hapus Pengajuan</option>
                            </select>
                        </div>
                    </div>

                    {{-- FORM CIF --}}
                    <div class="form-group row" id="form-cif">
                        <label class="col-sm-2 col-form-label">CIF</label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <select class="form-control" id="cif" style="width:100%; height: 38px; important"></select>
                            </div>
                        </div>
                    </div>


                    <!-- Tombol Proses -->
                    <div class="form-group row">
                        <div class="col-sm-6 offset-sm-2">
                            <button id="filterButton" class="btn btn-primary">
                                <i class="fas fa-search"></i> Proses
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DATA -->
            <div class="row mt-3" id="data-section" style="display: block;">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <strong>DATA</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered text-center mb-0">
                                <thead style="background: #f5f5f5;">
                                    <tr>
                                        <th>NOREK</th>
                                        <th>NAMA</th>
                                        <th>TGL AKAD</th>
                                        <th>CIF</th>
                                        <th>STATUS PENGAJUAN</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>

        $('#jenis_pengajuan').on('change', function () {
            let jenis = $(this).val();

            if (!jenis) return;

                // 👉 redirect khusus turun plafond
            if (jenis === 'turun_plafond') {
                window.location.href = "{{ url('/al/approval-pengajuan/turun-plafond') }}";
                return;
            }

            if (jenis === 'ajukan_kembali') {
                window.location.href = "{{ url('/al/approval-pengajuan/ajukan-kembali') }}";
                return;
            }

            if (jenis === 'hapus_pengajuan') {
                window.location.href = "{{ url('/al/approval-pengajuan/hapus') }}";
                return;
            }

        });

        $('#cif').select2({
            placeholder: 'Masukkan CIF',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: "{{ route('ajax.cif.hapus') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#filterButton').on('click', function (e) {
            e.preventDefault();

            let cif = $('#cif').val();

            if (!cif) {
                alert('Silakan pilih CIF terlebih dahulu');
                return;
            }

            if (!confirm('Yakin ingin menghapus pengajuan ini?')) return;

            $.ajax({
                url: "{{ route('hapus.proses') }}",
                type: "POST",
                data: {
                    cif: cif,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // reset select2
                        $('#cif').val(null).trigger('change');
                    });
                },
                error: function (xhr) {
                    let msg = 'Terjadi kesalahan';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: msg,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });



    </script>
@endsection

