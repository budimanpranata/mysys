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
                    <!-- Input Kode Kelompok -->
                    {{-- <div class="form-group row">
                        <label for="cif" class="col-sm-2 col-form-label">CIF</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="cif" placeholder="Masukkan CIF/Norek">
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label for="cif" class="col-sm-2 col-form-label">Cari CIF</label>
                        <div class="col-sm-6">
                            <select class="form-control select2-ajax" id="cif" style="width: 100%;">
                                <!-- Opsi akan di-load via AJAX -->
                            </select>
                        </div>
                    </div>
                    <!-- Input Tanggal Akad -->
                    <div class="form-group row">
                        <label for="jenis_transaksi" class="col-sm-2 col-form-label">Jenis Transaksi</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="jenis_transaksi">
                                <option value="" hidden>-- pilih jenis Transaksi --</option>
                                <option value="1">Simpanan</option>
                                <option value="2">Kartu Angsuran</option>
                            </select>
                        </div>
                    </div>
                    <!-- Tombol Cari -->
                    <div class="form-group row">
                        <div class="col-sm-6 offset-sm-2">
                            <button id="cetak" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
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
        $(document).ready(function() {
            // Inisialisasi Select2 dengan AJAX
            $('.select2-ajax').select2({
                placeholder: 'Cari CIF...',
                // minimumInputLength: 6, // Minimal karakter untuk mulai pencarian
                ajax: {
                    url: "{{ route('reportMutasi.getCif') }}", // Route untuk cari cif
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
        });

        $(document).ready(function () {
            $('#cetak').click(function () {
                var cif = $('#cif').val();
                var jenis = $('#jenis_transaksi').val();

                if (!cif || !jenis) {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'CIF dan jenis transaksi harus diisi!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Redirect ke route cetak pdf
                let url = `/report/mutasi/cetak-pdf?cif=${cif}&jenis=${jenis}`;
                window.open(url, '_blank'); // buka di tab baru
            });
        });
    </script>
@endpush
