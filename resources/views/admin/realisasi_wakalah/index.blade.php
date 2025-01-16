@extends('layouts.main')

@section('content-header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Realisasi Wakalah</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Realisasi Wakalah</li>
            </ol>
        </div>
    </div>
</div><!-- /.container-fluid -->
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Realisasi Wakalah</h3>
    </div>

    <div class="card-body">
        <div class="container mt-5">
            <h3>Realisasi Wakalah</h3>
            <div class="card p-4 mt-3">
                <form>
                    <div class="mb-3">
                        <label for="kodeKelompok" class="form-label">Kode Kelompok</label>
                        <input type="text" class="form-control" id="kodeKelompok"
                            placeholder="Masukkan kode kelompok">
                    </div>
                    <div class="mb-3">
                        <label for="tanggalRealisasi" class="form-label">Tanggal Realisasi</label>
                        <input type="date" class="form-control" id="tanggalRealisasi"
                            value="2024-12-04">
                    </div>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>
        </div>

    </div>
    <!-- /.card-body -->

    <!-- /.card-footer-->
</div>
@endsection