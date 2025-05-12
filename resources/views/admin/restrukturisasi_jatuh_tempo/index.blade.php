@extends('layouts.main')

@section('content-header')
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Restrukturisasi Jatuh Tempo</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Restrukturisasi Jatuh Tempo</li>
      </ol>
    </div>
  </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
  <div class="card mb-3">
    <div class="card-body">
      <form id="searchForm" method="POST" action="{{ route('jatuh_tempo.searchKelompok') }}">
        @csrf
        <div class="form-row align-items-end">
          <div class="col-md-4">
            <label for="kode_kelompok">Kode Kelompok</label>
            <input type="text" class="form-control" id="kode_kelompok" name="kode_kelompok"
              placeholder="Masukkan kode kelompok">
          </div>
          <input type="hidden" name="unit" id="userUnit" value="{{ auth()->user()->unit }}">
          <input type="hidden" name="param_tanggal" id="param_tanggal" value="{{ auth()->user()->param_tanggal }}">
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Cari</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="resultTable">
          <thead>
            <tr>
              <th>NO</th>
              <th>PILIH</th>
              <th>CIF</th>
              <th>NAMA</th>
              <th>KELOMPOK</th>
              <th>PEMBIAYAAN</th>
              <th>SALDO MARGIN</th>
              <th>POKOK</th>
              <th>JATUH TEMPO</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <tr id="emptyState">
              <td colspan="9" class="text-center">Silakan cari kode kelompok terlebih dahulu.</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="mt-3">
        <button type="button" class="btn btn-secondary" id="btnSelectAll">Select All</button>
        <button type="button" class="btn btn-warning" id="btnClearAll">Clear All</button>
        <button type="button" class="btn btn-success" id="btnRealisasi" disabled>Realisasi</button>
      </div>
    </div>
  </div>
</div>

@include('sweetalert::alert')

<script>
  $(document).ready(function () {
    // AJAX search
    $('#searchForm').submit(function (e) {
      e.preventDefault();
      let kodeKelompok = $('#kode_kelompok').val();
      let userUnit = $('#userUnit').val();
      let tableBody = $('#tableBody');
      if (!kodeKelompok) {
        Swal.fire("Error", "Kode Kelompok harus diisi!", "error");
        return;
      }
      $.ajax({
        url: "{{ route('jatuh_tempo.searchKelompok') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          kode_kelompok: kodeKelompok,
          unit: userUnit
        },
        success: function (response) {
          tableBody.empty();
          if (response.results && response.results.length > 0) {
            response.results.forEach(function (row, i) {
              let html = `<tr>
                <td>${i + 1}</td>
                <td><input type="checkbox" class="row-checkbox" name="selected[]" value="${row.id}"></td>
                <td>${row.cif}</td>
                <td>${row.nama}</td>
                <td>${row.code_kel}</td>
                <td>${parseInt(row.os).toLocaleString('id-ID')}</td>
                <td>${parseInt(row.saldo_margin).toLocaleString('id-ID')}</td>
                <td>${parseInt(row.pokok).toLocaleString('id-ID')}</td>
                <td>${parseInt(row.angsuran).toLocaleString('id-ID')}</td>
              </tr>`;
              tableBody.append(html);
            });
          } else {
            tableBody.append('<tr id="emptyState"><td colspan="9" class="text-center">Data tidak ditemukan.</td></tr>');
          }
          toggleRealisasiButton();
        },
        error: function (xhr) {
          tableBody.empty();
          tableBody.append('<tr id="emptyState"><td colspan="9" class="text-center">Terjadi kesalahan. Silakan coba lagi.</td></tr>');
        }
      });
    });
    // Select All
    $('#btnSelectAll').on('click', function () {
      $('.row-checkbox').prop('checked', true);
      toggleRealisasiButton();
    });
    // Clear All
    $('#btnClearAll').on('click', function () {
      $('.row-checkbox').prop('checked', false);
      toggleRealisasiButton();
    });
    // Row checkbox change
    $(document).on('change', '.row-checkbox', function () {
      toggleRealisasiButton();
    });
    // Toggle realisasi button
    function toggleRealisasiButton() {
      if ($('.row-checkbox:checked').length > 0) {
        $('#btnRealisasi').removeAttr('disabled');
      } else {
        $('#btnRealisasi').attr('disabled', true);
      }
    }
    // Realisasi button click
    $('#btnRealisasi').on('click', function (e) {
      e.preventDefault();
      let selectedCifs = $('.row-checkbox:checked').map(function () {
        return $(this).closest('tr').find('td').eq(2).text(); // field cif
      }).get();
      if (selectedCifs.length === 0) {
        Swal.fire("Warning", "Pilih minimal satu data untuk direalisasikan!", "warning");
        return;
      }
      Swal.fire({
        title: "Konfirmasi",
        text: "Apakah Anda yakin ingin melakukan restrukturisasi?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Lanjutkan",
        cancelButtonText: "Batal"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('jatuh_tempo.restrukturisasi') }}",
            type: "POST",
            data: {
              _token: "{{ csrf_token() }}",
              cifs: selectedCifs,
              unit: $('#userUnit').val(),
              kode_kelompok: $('#kode_kelompok').val(),
              param_tanggal: $('#param_tanggal').val()
            },
            success: function (response) {
              Swal.fire({
                title: "Sukses",
                text: response.message || "Restrukturisasi berhasil!",
                icon: "success"
              }).then(() => {
                // Remove processed rows from the table
                $('.row-checkbox:checked').each(function () {
                  $(this).closest('tr').remove();
                });
                // If table is empty, show empty state
                if ($('#tableBody tr').length === 0) {
                  $('#tableBody').append('<tr id="emptyState"><td colspan="9" class="text-center">Data tidak ditemukan.</td></tr>');
                }
                toggleRealisasiButton();
              });
            },
            error: function (xhr) {
              let errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Terjadi kesalahan. Coba lagi.";
              Swal.fire("Error", errorMsg, "error");
            }
          });
        }
      });
    });
  });
</script>
@endsection