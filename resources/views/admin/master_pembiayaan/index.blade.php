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
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
      </div>
      <div class="card-body">
        <!-- Search Form -->
        <form id="search-form" class="mb-4">
          <div class="form-group">
            <label>Kode Kelompok <span class="text-danger">*</span></label>
            <div class="row">
              <div class="col-sm-4">
                <input type="text" class="form-control" id="kode_kelompok" name="kode_kelompok"
                  placeholder="Masukkan Kode Kelompok" required>
              </div>
              <div class="col-sm-2">
                <button type="submit" class="btn btn-primary">Cari</button>
              </div>
            </div>
          </div>
        </form>

        <!-- Results Table -->
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Kelompok</th>
                <th>Kode Kel</th>
                <th>No Anggota</th>
                <th>CIF</th>
                <th>Nama</th>
                <th>Plafond</th>
                <th>OS</th>
                <th>Tenor</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="results-body">
              <tr>
                <td colspan="10" class="text-center">Belum ada data</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  $(function () {
    $('#search-form').on('submit', function (e) {
      e.preventDefault();
      const kodeKelompok = $('#kode_kelompok').val();

      if (!kodeKelompok) {
        Swal.fire({
          title: 'Peringatan!',
          text: 'Kode Kelompok harus diisi!',
          icon: 'warning',
          confirmButtonText: 'OK'
        });
        return;
      }

      // Show loading state
      $('#results-body').html('<tr><td colspan="10" class="text-center">Loading...</td></tr>');

      // Fetch data
      $.ajax({
        url: "{{ route('pembiayaan.data') }}",
        type: 'GET',
        data: { kode_kelompok: kodeKelompok },
        success: function (response) {
          if (response.status === 'success' && response.data && response.data.length > 0) {
            let html = '';
            response.data.forEach((item, index) => {
              html += `
              <tr>
                <td>${index + 1}</td>
                <td>${item.nama_kelompok}</td>
                <td>${item.kode_kel}</td>
                <td>${item.no_anggota}</td>
                <td>${item.anggota_cif}</td>
                <td>${item.nama_anggota}</td>
                <td>${item.plafond}</td>
                <td>${item.os}</td>
                <td>${item.tenor}</td>
                <td>
                  <a href="{{ route('pembiayaan.edit', '') }}/${item.anggota_cif}" class="btn btn-sm btn-primary">Edit</a>
                </td>
              </tr>
            `;
            });
            $('#results-body').html(html);
          } else {
            $('#results-body').html('<tr><td colspan="10" class="text-center">Tidak ada data ditemukan</td></tr>');
          }
        },
        error: function (xhr) {
          $('#results-body').html('<tr><td colspan="10" class="text-center text-danger">Terjadi kesalahan saat mengambil data</td></tr>');
          console.error('Error:', xhr);
        }
      });
    });
  });
</script>
@endpush
@endsection