@extends('layouts.main')

@section('content-header')
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Realisasi Murabahah</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Realisasi Murabahah</li>
      </ol>
    </div>
  </div>
</div><!-- /.container-fluid -->
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Realisasi Murabahah</h3>
  </div>

  <div class="card-body">
    <form>
      <div class="mb-3">
        <label for="kodeKelompok" class="form-label">Kode Kelompok</label>
        <input type="text" class="form-control" id="kodeKelompok" placeholder="Masukkan kode kelompok">
      </div>
      <div class="mb-3">
        <label for="tanggalRealisasi" class="form-label">Tanggal Realisasi</label>
        <input type="date" class="form-control" id="tanggalRealisasi" value="2024-12-04">
      </div>
      <button type="submit" class="btn btn-primary">Cari</button>
    </form>
  </div>
  <!-- /.card-body -->

  <!-- /.card-footer-->
</div>

<div class="mt-4">
  <table class="table table-bordered align-middle text-center">
    <thead class="table-light">
      <tr>
        <th scope="col">NO</th>
        <th scope="col">PILIH</th>
        <th scope="col">NAMA KELOMPOK</th>
        <th scope="col">NAMA</th>
        <th scope="col">PEMBIAYAAN</th>
        <th scope="col">MARGIN</th>
        <th scope="col">TGL MURABAHAH</th>
        <th scope="col">TGL JATUH TEMPO</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td><input type="checkbox" class="form-check-input"></td>
        <td>SUKSES JAYA</td>
        <td>GEANISA UTAMI</td>
        <td>2.000.000</td>
        <td>360.000</td>
        <td>2024-11-29</td>
        <td>2024-11-29</td>
      </tr>
      <tr>
        <td>2</td>
        <td><input type="checkbox" class="form-check-input"></td>
        <td>SUKSES JAYA</td>
        <td>GEANISA UTAMI</td>
        <td>2.000.000</td>
        <td>360.000</td>
        <td>2024-11-29</td>
        <td>2024-11-29</td>
      </tr>
      <tr>
        <td>3</td>
        <td><input type="checkbox" class="form-check-input"></td>
        <td>SUKSES JAYA</td>
        <td>GEANISA UTAMI</td>
        <td>2.000.000</td>
        <td>360.000</td>
        <td>2024-11-29</td>
        <td>2024-11-29</td>
      </tr>
    </tbody>
  </table>
  <div class="mt-3">
    <button class="btn btn-primary">Realisasi Pembiayaan</button>
  </div>
</div>
@endsection