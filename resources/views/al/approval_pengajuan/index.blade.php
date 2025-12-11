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
                        <label for="jenis_rest" class="col-sm-2 col-form-label">Jenis Pengajuan</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="jenis_rest">
                                <option value="" hidden>-- pilih jenis Pengajuan --</option>
                                <option value="1">Pembiayaan</option>
                                <option value="2">Approval</option>
                            </select>
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
                                        <th>AP</th>
                                        <th>CIF</th>
                                        <th>NAMA</th>
                                        <th>TANGGAL TRANSAKSI</th>
                                        <th>KELOMPOK</th>
                                        <th>NOMINAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td>CIAWI</td>
                                        <td>111DSC</td>
                                        <td>LILIS NURJANAH</td>
                                        <td>19-10-2024</td>
                                        <td>001-0234</td>
                                        <td>250.000</td>
                                    </tr>

                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td>CIAWI</td>
                                        <td>433ADA</td>
                                        <td>ITA MARSITA</td>
                                        <td>19-10-2024</td>
                                        <td>001-974</td>
                                        <td>75.000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-success mr-2">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button class="btn btn-danger">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
