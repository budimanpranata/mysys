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
</div>
@endsection

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Realisasi Murabahah</h3>
    </div>

    <div class="card-body">
      <form id="searchForm">
        <div class="mb-3">
          <label for="kodeKelompok" class="form-label">Kode Kelompok</label>
          <input type="text" class="form-control" id="kodeKelompok" placeholder="Masukkan kode kelompok" required>
        </div>
        <div class="mb-3">
          <label for="tanggalRealisasi" class="form-label">Tanggal Realisasi</label>
          <input type="date" class="form-control" id="tanggalRealisasi" required>
        </div>

        <!-- Hidden input to store unit -->
        <input type="hidden" name="unit" id="userUnit" value="{{ Auth::user()->unit }}">
        <!-- Hidden input to store id -->
        <input type="hidden" name="id" id="userId" value="{{ Auth::user()->role_id }}">
        <!-- Hidden input to store param tanggal -->
        <input type="hidden" name="id" id="userDate" value="{{ Auth::user()->param_tanggal }}">

        <button type="submit" class="btn btn-primary">Cari</button>
      </form>
    </div>

    <div class="mt-2">
      <div class="card-body">
        <table class="table table-bordered align-middle text-center">
          <thead class="table-light">
            <tr>
              <th>NO</th>
              <th>PILIH</th>
              <th>NAMA KELOMPOK</th>
              <th>NAMA</th>
              <th>PEMBIAYAAN</th>
              <th>MARGIN</th>
              <th>TGL MURABAHAH</th>
              <th>TGL JATUH TEMPO</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <tr id="emptyState">
              <td colspan="8">Belum ada data</td>
            </tr>
          </tbody>
        </table>
        <div class="mt-3">
          <button id="realisasiBtn" class="btn btn-primary" disabled>Realisasi Pembiayaan</button>
        </div>
      </div>
    </div>
  </div>
</div>

@include('sweetalert::alert')

<script>
  $(document).ready(function () {
    // Search Form Submission
    $('#searchForm').submit(function (e) {
      e.preventDefault();
      let kodeKelompok = $('#kodeKelompok').val();
      let tanggalRealisasi = $('#tanggalRealisasi').val();
      let userUnit = $('#userUnit').val();

      if (!kodeKelompok || !tanggalRealisasi) {
        Swal.fire("Error", "Kode Kelompok dan Tanggal Realisasi harus diisi!", "error");
        return;
      }

      $.ajax({
        url: "{{ route('realisasi.search') }}",
        type: "GET",
        data: { kode_kel: kodeKelompok, tgl_akad: tanggalRealisasi, unit: userUnit },
        success: function (response) {
          let tableBody = $('#tableBody');
          tableBody.empty();

          if (response.length === 0) {
            tableBody.append('<tr><td colspan="8">Belum ada data</td></tr>');
          } else {
            response.forEach((item, index) => {
              let row = `
                <tr data-id="${item.cif}">
                  <td>${index + 1}</td>
                  <td><input type="checkbox" class="selectRecord"></td>
                  <td>SUKSES JAYA</td>
                  <td>GEANISA UTAMI</td>
                  <td>Rp. 2.000.000</td>
                  <td>Rp. 360.000</td>
                  <td>2024-11-29</td>
                  <td>2024-11-29</td>
                </tr>`;
              tableBody.append(row);
            });
          }
        }
      });
    });

    // Enable/Disable Realisasi Button
    $(document).on('change', '.selectRecord', function () {
      let anyChecked = $('.selectRecord:checked').length > 0;
      $('#realisasiBtn').prop('disabled', !anyChecked);
    });

    // Fix: Use event delegation for dynamically loaded elements
    $(document).on('click', '#realisasiBtn', function () {
      let kodeKelompok = $('#kodeKelompok').val();
      let tanggalRealisasi = $('#tanggalRealisasi').val();
      let userUnit = $('#userUnit').val();
      let userId = $('#userId').val();
      let userDate = $('#userDate').val();
      let selectedCifs = $('.selectRecord:checked').map(function () {
        return $(this).closest('tr').data('id');
      }).get();

      if (selectedCifs.length === 0) {
        Swal.fire("Warning", "Pilih minimal satu data untuk direalisasikan!", "warning");
        return;
      }

      Swal.fire({
        title: "Konfirmasi",
        text: "Apakah Anda yakin ingin merealisasikan pembiayaan?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Lanjutkan",
        cancelButtonText: "Batal"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('realisasi.update') }}",
            type: "POST",
            data: {
              _token: "{{ csrf_token() }}",
              cifs: selectedCifs,
              kode_kel: kodeKelompok,
              tgl_akad: tanggalRealisasi,
              unit: userUnit,
              id: userId,
              param_tanggal: userDate
            },
            success: function (response) {
              Swal.fire("Sukses", "Status berhasil diperbarui!", "success");
              resetPage();
            },
            error: function () {
              Swal.fire("Error", "Terjadi kesalahan, coba lagi!", "error");
            }
          });
        }
      });
    });

    function resetPage() {
      $('#searchForm')[0].reset();
      $('#tableBody').load(location.href + ' #tableBody');
      $('#realisasiBtn').prop('disabled', true);
    }
  });
</script>
@endsection