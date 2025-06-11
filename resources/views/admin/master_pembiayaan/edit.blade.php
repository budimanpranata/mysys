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

  async function cariKtp() {
    const nik = document.getElementById('ktp').value;
    const resultContainer = document.getElementById('resultContainer');

    // Kosongkan hasil sebelumnya
    resultContainer.innerHTML = '';

    if (!nik) {
      Swal.fire({
        title: 'Peringatan!',
        text: 'NIK harus diisi!',
        icon: 'warning',
        confirmButtonText: 'OK'
      });
      return;
    }

    try {
      // Kirim request ke controller Laravel
      const response = await fetch('/anggota/cari-ktp', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ nik })
      });

      const data = await response.json();

      if (!data.data || !data.data[0]) {
        throw new Error('Data tidak ditemukan');
      }

      // Tampilkan hasil pencarian
      resultContainer.innerHTML = `
        <div class="col-sm-11">
          <div class="card mb-3">
            <div class="card-header">KTP</div>
            <div class="card-body">
              <img src="http://rmc.nurinsani.co.id:9373/berkas/${data.data[0].ktp}" 
                width="580px" 
                alt="Gambar KTP" 
                class="img-thumbnail" 
                style="cursor: pointer;" 
                data-bs-toggle="modal" 
                data-bs-target="#imageModal"
                onclick="openImageModal('http://rmc.nurinsani.co.id:9373/berkas/${data.data[0].ktp}')">
            </div>
          </div>
        </div>

        <div class="col-sm-11">
          <div class="card mb-3">
            <div class="card-header">Kartu Keluarga</div>
            <div class="card-body">
              <img src="http://rmc.nurinsani.co.id:9373/berkas/${data.data[0].kk}" 
                width="580px" 
                alt="Gambar KK" 
                class="img-thumbnail" 
                style="cursor: pointer;" 
                data-bs-toggle="modal" 
                data-bs-target="#imageModal"
                onclick="openImageModal('http://rmc.nurinsani.co.id:9373/berkas/${data.data[0].kk}')">
            </div>
          </div>
        </div>
      `;
    } catch (error) {
      // Tampilkan pesan error
      resultContainer.innerHTML = `
        <div class="col-sm-11">
          <div class="card">
            <div class="card-body">
              <p style="color: red;">Data tidak ditemukan</p>
          </div>
        </div>
      `;
    }
  }

  function openImageModal(imageUrl) {
    // Set sumber gambar di modal
    document.getElementById('modalImage').src = imageUrl;
  }
</script>
@endpush
@endsection