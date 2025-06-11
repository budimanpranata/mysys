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

@include('admin.master_pembiayaan.form-edit')

@push('scripts')
<script>
  $(function () {
    // Set the suffix value from pembiayaan data
    $('#suffix').val('{{ $pembiayaan->suffix }}');

    // Handle form submission
    $('#form').on('submit', function (e) {
      e.preventDefault();

      // Show loading state
      Swal.fire({
        title: 'Memproses...',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      // Submit form via AJAX
      $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (response) {
          Swal.close();

          if (response.status === 'success') {
            Swal.fire({
              title: 'Berhasil!',
              text: response.message || 'Data berhasil disimpan',
              icon: 'success',
              confirmButtonText: 'OK'
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = "{{ route('pembiayaan.index') }}";
              }
            });
          } else if (response.status === 'warning') {
            Swal.fire({
              title: 'Peringatan!',
              text: response.message,
              icon: 'warning',
              confirmButtonText: 'OK'
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = "{{ route('pembiayaan.index') }}";
              }
            });
          } else {
            Swal.fire({
              title: 'Error!',
              text: response.message || 'Terjadi kesalahan saat menyimpan data',
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        },
        error: function (xhr) {
          Swal.close();
          Swal.fire({
            title: 'Error!',
            text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data',
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      });
    });
  });
</script>
@endpush
@endsection