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
                            <label for="keterangan_transaksi" class="col-sm-2 col-form-label">Keterangan Tra</label>
                            <div class="col-sm-6">
                                <textarea name="keterangan_transaksi" class="form-control" id="keterangan_transaksi" cols="2" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kode_rekening" class="col-sm-2 col-form-label">Rekening</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="kode_rekening">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kredit" class="col-sm-2 col-form-label">Jumlah (Rp)</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" id="kredit">
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
    
@endpush
