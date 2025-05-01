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
<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Hapus Buku</h3>
    </div>

    <div class="card-body">
      <form id="searchForm">
        <div class="mb-3">
          <label for="cif" class="form-label">CIF Anggota</label>
          <input type="text" class="form-control" id="cif" name="cif" placeholder="Masukkan nomor CIF anggota" required>
        </div>

        <!-- Hidden input to store unit -->
        <input type="hidden" name="unit" id="userUnit" value="{{ Auth::user()->unit }}">
        <!-- Hidden input to store id -->
        <input type="hidden" name="id" id="userId" value="{{ Auth::user()->role_id }}">
        <!-- Hidden input to store param tanggal -->
        <input type="hidden" name="param_tanggal" id="userDate" value="{{ Auth::user()->param_tanggal }}">

        <button type="submit" class="btn btn-primary">Cari</button>
      </form>
    </div>

    <div class="mt-2">
      <div class="card-body">
        <table class="table table-bordered align-middle text-center">
          <thead class="table-light">
            <tr>
              <th>NO</th>
              <th>KODE REKENING</th>
              <th>NAMA REKENING</th>
              <th>KETERANGAN</th>
              <th>DEBIT</th>
              <th>KREDIT</th>
              <th>AKSI</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <tr id="emptyState">
              <td colspan="7">Belum ada data</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@include('admin.hapus_buku.form-proses')

@include('sweetalert::alert')

<script>
  $(document).ready(function () {
    $('#searchForm').on('submit', function (e) {
      e.preventDefault();

      const cif = $('#cif').val();
      const unit = $('#userUnit').val();

      // Clear existing table data
      $('#tableBody').empty();

      // Make AJAX request
      $.ajax({
        url: "{{ route('hapus_buku.data') }}",
        method: 'GET',
        data: {
          cif: cif,
          unit: unit
        },
        success: function (response) {
          $('#tableBody').empty();

          if (response.data && response.data.length > 0) {
            response.data.forEach((item, index) => {
              const row = `
                <tr>
                  <td>${index + 1}</td>
                  <td>${item.no_anggota || '-'}</td>
                  <td>${item.nama || '-'}</td>
                  <td>${item.keterangan || '-'}</td>
                  <td>${item.debit || 0}</td>
                  <td>${item.kredit || 0}</td>
                  <td>
                    <button class="btn btn-primary btn-sm process-btn" 
                      data-cif="${item.cif}"
                      data-nama="${item.nama}"
                      data-rekening="${item.no_anggota}"
                      data-debit="${item.debit || 0}"
                      data-kredit="${item.kredit || 0}"
                      data-simpanan="${item.simpanan || 0}"
                      data-minggu-ke="${item.minggu_ke || 0}"
                      data-nomor-bukti="${item.nomor_bukti}">
                        Proses
                    </button>
                  </td>
                </tr>
              `;
              $('#tableBody').append(row);
            });
          } else {
            $('#tableBody').html('<tr><td colspan="7">Tidak ada data ditemukan</td></tr>');
          }
        },
        error: function (xhr, status, error) {
          $('#tableBody').html('<tr><td colspan="7">Terjadi kesalahan saat mengambil data</td></tr>');
          console.error(error);
        }
      });
    });

    // Handle process button click
    $(document).on('click', '.process-btn', function () {
      const cif = $(this).data('cif');
      const nama = $(this).data('nama');
      const rekening = $(this).data('rekening')
      const debit = $(this).data('debit');
      const kredit = $(this).data('kredit');
      const simpanan = $(this).data('simpanan');
      const mingguKe = $(this).data('minggu-ke');
      const nomorBukti = $(this).data('nomor-bukti');

      // Set the values in the modal
      $('#modal_cif').val(cif);
      $('#tanggal').val(new Date().toISOString().split('T')[0]);
      $('#pokok').val(debit);
      $('#margin').val(kredit);
      $('#simpanan').val(simpanan);
      $('#minggu_ke').val(mingguKe);
      $('#nomor_bukti').val(nomorBukti);
      $('#no_anggota').val(rekening);

      $('#prosesModal').modal('show');
    });

    // Handle form submission
    $('#submitProses').on('click', function () {
      // Get all form data
      const formData = {
        _token: '{{ csrf_token() }}',
        nomor_bukti: $('#nomor_bukti').val(),
        tanggal: $('#tanggal').val(),
        cif: $('#modal_cif').val(),
        pokok: $('#pokok').val(),
        margin: $('#margin').val(),
        minggu_ke: $('#minggu_ke').val(),
        simpanan: $('#simpanan').val(),
        jenis_wo: $('#jenis_wo').val(),
        no_anggota: $('#no_anggota').val(),
        userUnit: $('#userUnit').val(),
        userId: $('#userId').val(),
        userDate: $('#userDate').val()
      };

      // Validate form
      if (!$('#formProses')[0].checkValidity()) {
        $('#formProses')[0].reportValidity();
        return;
      }

      // Send request to process
      $.ajax({
        url: "{{ route('hapus_buku.jurnal') }}",
        method: 'POST',
        data: formData,
        success: function (response) {
          $('#prosesModal').modal('hide');

          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data telah berhasil diproses',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
          }).then((result) => {
            if (result.isConfirmed) {
              resetPage();
            }
          });
        },
        error: function (xhr, status, error) {
          let errorMessage = 'Terjadi kesalahan saat memproses data.';
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
          }

          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: errorMessage,
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#d33'
          });
        }
      });
    });

    function resetPage() {
      $('#searchForm')[0].reset();
      $('#formProses')[0].reset();
      $('#tableBody').empty().append('<tr id="emptyState"><td colspan="7">Belum ada data</td></tr>');
    }
  });
</script>
@endsection