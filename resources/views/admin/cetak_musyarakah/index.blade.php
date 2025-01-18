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
      <form id="musyarakahForm" action="{{ route('form_musyarakah') }}" method="POST">
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

<script>
  $(document).ready(function () {
    // CSRF token dan global AJAX error handling
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $(document).ajaxError(function () {
      Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error');
    });

    // Handle form submission
    $('#musyarakahForm').on('submit', function (e) {
      e.preventDefault();

      const form = $(this);
      const url = form.attr('action');
      const data = form.serialize();

      $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (response) {
          if (response.success) {
            Swal.fire('Berhasil!', response.message, 'success');
            // Load iframe PDF on success
            $('.card-body').html(`
              <iframe src="${response.iframe_url}" width="100%" height="800" frameborder="0"></iframe>
            `);
          } else {
            Swal.fire('Oops!', response.message, 'error');
          }
        },
        error: function () {
          Swal.fire('Error!', 'Terjadi kesalahan saat memproses data.', 'error');
        }
      });
    });
  });
</script>
@endsection