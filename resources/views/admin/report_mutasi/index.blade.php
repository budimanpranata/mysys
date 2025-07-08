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
                        <label for="cif" class="col-sm-2 col-form-label">CIF</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="cif" placeholder="Masukkan CIF/Norek">
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
        $(document).ready(function () {
            $('#cetak').click(function () {
                var cif = $('#cif').val();
                var jenis = $('#jenis_transaksi').val();

                if (!cif || !jenis) {
                    alert('CIF dan jenis transaksi wajib diisi!');
                    return;
                }

                // Redirect ke route cetak pdf
                let url = `/report/mutasi/cetak-pdf?cif=${cif}&jenis=${jenis}`;
                window.open(url, '_blank'); // buka di tab baru
            });
        });
    </script>
@endpush
