@extends('layouts.main')

@section('content-header')
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Cetak Musyarakah</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Cetak Musyarakah</li>
      </ol>
    </div>
  </div>
</div>
<!-- /.container-fluid -->
@endsection

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Cetak Musyarakah</h3>
    </div>

    <div class="card-body">
      <form action="{{ route('form_musyarakah') }}" method="POST" id="musyarakahForm">
        @csrf
        <div class="mb-3">
          <label for="tanggalCetak" class="form-label">Tanggal Cetak</label>
          <input type="date" name="tanggal_cetak" class="form-control @error('tanggal_cetak') is-invalid @enderror"
            id="tanggalCetak" required>
          @error('tanggal_cetak')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cari</button>
      </form>
    </div>
  </div>
</div>
<!-- /.container-fluid -->

<!-- Include SweetAlert Notification -->
@include('sweetalert::alert')
@endsection