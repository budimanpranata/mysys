@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2>Selamat Datang, {{ Auth::user()->name }}</h2>
                <h5 class="mt-3">SysDate : {{ Auth::user()->param_tanggal }}</h5>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Cetak Cs</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('cetak.cs.index') }}" class="btn btn-primary float-right">Back</a>
        </div>
        <div class="card-body">
            <iframe src="data:application/pdf;base64,{{ base64_encode($pdf->output()) }}"
                style="width: 100%; height: 600px;" frameborder="0"></iframe>
        </div>
    </div>
@endsection
