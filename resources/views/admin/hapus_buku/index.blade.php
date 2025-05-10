@extends('layouts.main')

@section('content-header')
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Hapus Buku</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Hapus Buku</li>
      </ol>
    </div>
  </div>
</div>
@endsection

@section('content')
<style>
  .cif-container {
    position: relative;
  }

  #cifList {
    position: absolute;
    z-index: 1000;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  #cifList .list-group-item {
    padding: 8px 12px;
    cursor: pointer;
  }

  #cifList .list-group-item:hover {
    background-color: #f8f9fa;
  }
</style>

<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Form Hapus Buku</h3>
    </div>

    <div class="card-body">
      <form id="hapusBukuForm">
        <div class="form-group mb-3">
          <label for="nomor_bukti">Nomor Bukti</label>
          <input type="text" class="form-control" id="nomor_bukti" name="nomor_bukti" readonly>
        </div>

        <div class="form-group mb-3 cif-container">
          <label for="cif">CIF</label>
          <input type="text" class="form-control" id="cif" name="cif" required>
          <div id="cifList" class="list-group"></div>
        </div>

        <div class="form-group mb-3">
          <label for="tanggal">Tanggal</label>
          <input type="date" class="form-control" id="tanggal" name="tanggal" required value="{{ date('Y-m-d') }}">
        </div>

        <div class="form-group mb-3">
          <label for="pokok">Pokok</label>
          <input type="number" class="form-control" id="pokok" name="pokok" readonly>
        </div>

        <div class="form-group mb-3">
          <label for="margin">Margin</label>
          <input type="number" class="form-control" id="margin" name="margin" readonly>
        </div>

        <div class="form-group mb-3">
          <label for="minggu_ke">Minggu-ke</label>
          <input type="number" class="form-control" id="minggu_ke" name="minggu_ke" readonly>
        </div>

        <div class="form-group mb-3">
          <label for="simpanan">Simpanan</label>
          <input type="number" class="form-control" id="simpanan" name="simpanan" readonly>
        </div>

        <div class="form-group mb-3">
          <label for="jenis_wo">Jenis WO</label>
          <select class="form-control" id="jenis_wo" name="jenis_wo" required>
            <option value="">Pilih Jenis WO</option>
            <option value="NPF">NPF</option>
            <option value="Meninggal Dunia">Meninggal Dunia</option>
          </select>
        </div>

        <!-- Hidden inputs -->
        <input type="hidden" name="no_anggota" id="no_anggota">
        <input type="hidden" name="unit" id="userUnit" value="{{ Auth::user()->unit }}">
        <input type="hidden" name="id" id="userId" value="{{ Auth::user()->role_id }}">
        <input type="hidden" name="param_tanggal" id="userDate" value="{{ Auth::user()->param_tanggal }}">
        <input type="hidden" name="nama" id="nama">

        <button type="submit" class="btn btn-primary">Simpan</button>
      </form>
    </div>

    <div class="card-body">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>NO</th>
            <th>NOMOR BUKTI</th>
            <th>CIF</th>
            <th>NAMA</th>
            <th>POKOK</th>
            <th>MARGIN</th>
            <th>JENIS WO</th>
            <th>AKSI</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          <tr id="emptyState">
            <td colspan="8">Belum ada data</td>
          </tr>
        </tbody>
      </table>

      <div class="mt-3">
        <button type="button" class="btn btn-success" id="btnSelesai">Selesai</button>
      </div>
    </div>
  </div>
</div>

@include('sweetalert::alert')

<script>
  $(document).ready(function () {
    // Generate nomor bukti on page load
    generateNomorBukti();

    // Function to generate nomor bukti
    function generateNomorBukti() {
      const unit = $('#userUnit').val();
      const randomStr = Math.random().toString(36).substring(2, 9); // Generate 7 character string
      $('#nomor_bukti').val('BS-' + unit + '-' + randomStr);
    }

    // Handle CIF input
    let searchTimeout;
    $('#cif').on('keyup', function () {
      const searchTerm = $(this).val();
      const unit = $('#userUnit').val();

      // Clear previous timeout
      clearTimeout(searchTimeout);

      // Clear previous results
      $('#cifList').empty();

      // Set new timeout
      searchTimeout = setTimeout(function () {
        if (searchTerm.length > 0) {
          $.ajax({
            url: "{{ route('hapus_buku.search_cif') }}",
            method: 'GET',
            data: {
              term: searchTerm,
              unit: unit
            },
            success: function (data) {
              $('#cifList').empty();
              if (data.length > 0) {
                data.forEach(function (item) {
                  $('#cifList').append(`
                  <a href="#" class="list-group-item list-group-item-action cif-item" 
                     data-cif="${item.cif}"
                     data-no-anggota="${item.no_anggota}"
                     data-nama="${item.nama}"
                     data-plafond="${item.plafond}"
                     data-saldo-margin="${item.saldo_margin}"
                     data-run-tenor="${item.run_tenor}"
                     data-simpanan="${item.simpanan}">
                    ${item.cif} - ${item.nama}
                  </a>
                `);
                });
              } else {
                $('#cifList').append('<div class="list-group-item">Tidak ada data ditemukan</div>');
              }
            },
            error: function (xhr) {
              console.error('Error:', xhr);
              $('#cifList').append('<div class="list-group-item">Error mencari data</div>');
            }
          });
        }
      }, 300); // Wait 300ms after last keyup before searching
    });

    // Handle CIF item selection
    $(document).on('click', '.cif-item', function (e) {
      e.preventDefault();
      const item = $(this);

      // Populate form fields
      $('#cif').val(item.data('cif'));
      $('#no_anggota').val(item.data('no-anggota'));
      $('#nama').val(item.data('nama'));
      $('#pokok').val(item.data('plafond'));
      $('#margin').val(item.data('saldo-margin'));
      $('#minggu_ke').val(item.data('run-tenor'));
      $('#simpanan').val(item.data('simpanan'));

      // Clear the list
      $('#cifList').empty();
    });

    // Close cifList when clicking outside
    $(document).on('click', function (e) {
      if (!$(e.target).closest('#cif, #cifList').length) {
        $('#cifList').empty();
      }
    });

    // Handle form submission
    $('#hapusBukuForm').on('submit', function (e) {
      e.preventDefault();

      const formData = {
        _token: '{{ csrf_token() }}',
        nomor_bukti: $('#nomor_bukti').val(),
        tanggal: $('#tanggal').val(),
        cif: $('#cif').val(),
        pokok: $('#pokok').val(),
        margin: $('#margin').val(),
        minggu_ke: $('#minggu_ke').val(),
        simpanan: $('#simpanan').val(),
        jenis_wo: $('#jenis_wo').val(),
        no_anggota: $('#no_anggota').val(),
        userUnit: $('#userUnit').val(),
        userId: $('#userId').val(),
        userDate: $('#userDate').val(),
        nama: $('#nama').val()
      };

      $.ajax({
        url: "{{ route('hapus_buku.add_transaction') }}",
        method: 'POST',
        data: formData,
        success: function (response) {
          if (response.success) {
            // Add row to table
            addRowToTable(formData);
            // Reset form except nomor_bukti
            $('#hapusBukuForm')[0].reset();
            // Generate new nomor_bukti
            generateNomorBukti();
            // Show success message
            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: 'Data berhasil ditambahkan'
            });
          }
        },
        error: function (xhr) {
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: xhr.responseJSON.message || 'Terjadi kesalahan'
          });
        }
      });
    });

    // Handle delete button click
    $(document).on('click', '.btn-delete', function () {
      const nomor_bukti = $(this).data('nomor-bukti');
      const row = $(this).closest('tr');

      Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data akan dihapus dari daftar",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('hapus_buku.delete_transaction') }}",
            method: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              nomor_bukti: nomor_bukti
            },
            success: function (response) {
              if (response.success) {
                row.remove();
                if ($('#tableBody tr').length === 0) {
                  $('#tableBody').html('<tr id="emptyState"><td colspan="8">Belum ada data</td></tr>');
                }
                Swal.fire('Terhapus!', 'Data berhasil dihapus', 'success');
              }
            },
            error: function (xhr) {
              Swal.fire('Gagal!', xhr.responseJSON.message || 'Terjadi kesalahan', 'error');
            }
          });
        }
      });
    });

    // Handle selesai button click
    $('#btnSelesai').on('click', function () {
      const rows = $('#tableBody tr:not(#emptyState)');
      if (rows.length === 0) {
        Swal.fire('Peringatan!', 'Tidak ada data untuk diproses', 'warning');
        return;
      }

      Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Semua data akan diproses",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, proses!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          const data = [];
          rows.each(function () {
            data.push($(this).data('record'));
          });

          $.ajax({
            url: "{{ route('hapus_buku.process_all') }}",
            method: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              records: data
            },
            success: function (response) {
              if (response.success) {
                $('#tableBody').html('<tr id="emptyState"><td colspan="8">Belum ada data</td></tr>');
                Swal.fire('Berhasil!', 'Semua data telah diproses', 'success');
              }
            },
            error: function (xhr) {
              Swal.fire('Gagal!', xhr.responseJSON.message || 'Terjadi kesalahan', 'error');
            }
          });
        }
      });
    });

    function addRowToTable(data) {
      const rowCount = $('#tableBody tr:not(#emptyState)').length + 1;
      const row = `
      <tr data-record='${JSON.stringify(data)}'>
        <td>${rowCount}</td>
        <td>${data.nomor_bukti}</td>
        <td>${data.cif}</td>
        <td>${data.nama}</td>
        <td>${data.pokok}</td>
        <td>${data.margin}</td>
        <td>${data.jenis_wo}</td>
        <td>
          <button type="button" class="btn btn-danger btn-sm btn-delete" data-nomor-bukti="${data.nomor_bukti}">
            Hapus
          </button>
        </td>
      </tr>
    `;

      if ($('#emptyState').length) {
        $('#tableBody').empty();
      }
      $('#tableBody').append(row);
    }
  });
</script>
@endsection