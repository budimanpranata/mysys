@extends('layouts.main')

@section('content-header')
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Restrukturisasi by Kelompok</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Restrukturisasi by Kelompok</li>
      </ol>
    </div>
  </div>
</div>
@endsection

@section('content')
<style>
  .kelompok-container {
    position: relative;
  }

  #kelompokList {
    position: absolute;
    z-index: 1000;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  #kelompokList .list-group-item {
    padding: 8px 12px;
    cursor: pointer;
  }

  #kelompokList .list-group-item:hover {
    background-color: #f8f9fa;
  }
</style>
<div class="container-fluid">
  <div class="card mb-3">
    <div class="card-body">
      <form id="searchForm">
        @csrf
        <div class="form-group mb-3 kelompok-container">
          <label for="kode_kelompok">Kode Kelompok</label>
          <input type="text" class="form-control" id="kode_kelompok" name="kode_kelompok" autocomplete="off"
            placeholder="Masukkan kode kelompok">
          <div id="kelompokList" class="list-group"></div>
        </div>
        <div class="form-group mb-3">
          <label for="jenis_rest">Jenis Restrukturisasi</label>
          <select class="form-control" id="jenis_rest" name="jenis_rest" required>
            <option value="Pokok">Pokok</option>
            <option value="Pokok+Margin">Pokok + Margin</option>
          </select>
        </div>
        <div class="form-group mb-3">
          <label for="dari_simpanan">Dari Simpanan</label>
          <select class="form-control" id="dari_simpanan" name="dari_simpanan" required>
            <option value="Tidak">Tidak</option>
            <option value="Ya">Ya</option>
          </select>
        </div>
        <div class="form-group mb-3">
          <label for="tenor">Tenor</label>
          <input type="number" class="form-control" id="tenor" name="tenor" min="1" required>
        </div>
        <input type="hidden" name="unit" id="userUnit" value="{{ auth()->user()->unit }}">
        <input type="hidden" name="param_tanggal" id="param_tanggal" value="{{ auth()->user()->param_tanggal }}">
        <div class="form-group mb-3">
          <button type="submit" class="btn btn-primary">Cari</button>
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
              <th>No</th>
              <th>Kode Kelompok</th>
              <th>Kode AO</th>
              <th>Nama Kelompok</th>
              <th>NOA</th>
              <th>Total Pembiayaan</th>
              <th>Tenor Baru</th>
              <th>Disburse</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <tr id="emptyState">
              <td colspan="8" class="text-center">Silakan cari kode kelompok terlebih dahulu.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@include('sweetalert::alert')
<script>
  $(document).ready(function () {
    // Auto-suggestion for kode kelompok
    let searchTimeout;
    $('#kode_kelompok').on('keyup', function () {
      const searchTerm = $(this).val();
      const unit = $('#userUnit').val();
      clearTimeout(searchTimeout);
      $('#kelompokList').empty();
      searchTimeout = setTimeout(function () {
        if (searchTerm.length > 0) {
          $.ajax({
            url: "{{ route('rest_kelompok.suggest_kelompok') }}",
            method: 'GET',
            data: { term: searchTerm, unit: unit },
            success: function (data) {
              $('#kelompokList').empty();
              if (data.length > 0) {
                data.forEach(function (item) {
                  $('#kelompokList').append(`
                  <a href="#" class="list-group-item list-group-item-action kelompok-item"
                     data-kode-kelompok="${item.code_kel}"
                     data-nama-kelompok="${item.nama_kel}"
                     data-cao="${item.cao}">
                    ${item.code_kel} - ${item.nama_kel}
                  </a>
                `);
                });
              } else {
                $('#kelompokList').append('<div class="list-group-item">Tidak ada data ditemukan</div>');
              }
            },
            error: function () {
              $('#kelompokList').append('<div class="list-group-item">Error mencari data</div>');
            }
          });
        }
      }, 300);
    });
    // Select kelompok from suggestion
    $(document).on('click', '.kelompok-item', function (e) {
      e.preventDefault();
      $('#kode_kelompok').val($(this).data('kode-kelompok'));
      $('#kelompokList').empty();
    });
    // Close kelompokList when clicking outside
    $(document).on('click', function (e) {
      if (!$(e.target).closest('#kode_kelompok, #kelompokList').length) {
        $('#kelompokList').empty();
      }
    });
    // Search form submit
    $('#searchForm').submit(function (e) {
      e.preventDefault();
      let kodeKelompok = $('#kode_kelompok').val();
      let userUnit = $('#userUnit').val();
      let jenisRest = $('#jenis_rest').val();
      let dariSimpanan = $('#dari_simpanan').val();
      let tenor = $('#tenor').val();
      let tableBody = $('#tableBody');
      if (!kodeKelompok || !jenisRest || !dariSimpanan || !tenor) {
        Swal.fire("Error", "Semua field harus diisi!", "error");
        return;
      }
      $.ajax({
        url: "{{ route('rest_kelompok.searchKelompok') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          kode_kelompok: kodeKelompok,
          unit: userUnit,
          jenis_rest: jenisRest,
          dari_simpanan: dariSimpanan,
          tenor: tenor
        },
        success: function (response) {
          tableBody.empty();
          if (response.results && response.results.length > 0) {
            response.results.forEach(function (row, i) {
              let html = `<tr data-kode-kelompok="${row.code_kel}" data-cao="${row.cao}" data-nama-kelompok="${row.nama_kel}" data-noa="${row.noa}" data-total-pembiayaan="${row.total_pembiayaan}" data-tenor="${row.tenor}">
              <td>${i + 1}</td>
              <td>${row.code_kel}</td>
              <td>${row.cao}</td>
              <td>${row.nama_kel}</td>
              <td>${row.noa}</td>
              <td>${parseInt(row.total_pembiayaan).toLocaleString('id-ID')}</td>
              <td>${row.tenor}</td>
              <td><button type="button" class="btn btn-success btn-sm btn-disburse">Disburse</button></td>
            </tr>`;
              tableBody.append(html);
            });
          } else {
            tableBody.append('<tr id="emptyState"><td colspan="8" class="text-center">Data tidak ditemukan.</td></tr>');
          }
        },
        error: function () {
          tableBody.empty();
          tableBody.append('<tr id="emptyState"><td colspan="8" class="text-center">Terjadi kesalahan. Silakan coba lagi.</td></tr>');
        }
      });
    });
    // Disburse button click
    $(document).on('click', '.btn-disburse', function () {
      let row = $(this).closest('tr');
      let kodeKelompok = row.data('kode-kelompok');
      let userUnit = $('#userUnit').val();
      let paramTanggal = $('#param_tanggal').val();
      let jenisRest = $('#jenis_rest').val();
      let dariSimpanan = $('#dari_simpanan').val();
      let tenor = $('#tenor').val();
      Swal.fire({
        title: "Konfirmasi",
        text: `Apakah Anda yakin ingin melakukan restrukturisasi untuk kelompok ${kodeKelompok}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Lanjutkan",
        cancelButtonText: "Batal"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('rest_kelompok.restrukturisasi') }}",
            type: "POST",
            data: {
              _token: "{{ csrf_token() }}",
              kode_kelompok: kodeKelompok,
              unit: userUnit,
              param_tanggal: paramTanggal,
              jenis_rest: jenisRest,
              dari_simpanan: dariSimpanan,
              tenor: tenor
            },
            success: function (response) {
              Swal.fire({
                title: "Sukses",
                text: response.message || "Restrukturisasi berhasil!",
                icon: "success"
              }).then(() => {
                // Reset form and table after success
                $('#searchForm')[0].reset();
                $('#tableBody').html('<tr id="emptyState"><td colspan="8" class="text-center">Silakan cari kode kelompok terlebih dahulu.</td></tr>');
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