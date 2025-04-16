@extends('layouts.main')

@section('content-header')
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Pembatalan Wakalah</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Pembatalan Wakalah</li>
      </ol>
    </div>
  </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Pembatalan Wakalah</h3>
    </div>

    <div class="card-body">
      <table id="wakalahTable" class="table table-bordered table-striped align-middle text-center">
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
      </table>

      <!-- Hidden input to store id -->
      <input type="hidden" name="id" id="userId" value="{{ Auth::user()->role_id }}">
      <!-- Hidden input to store param tanggal -->
      <input type="hidden" name="param_tanggal" id="userDate" value="{{ Auth::user()->param_tanggal }}">

      <div class="mt-3">
        <button id="realisasiBtn" class="btn btn-primary" disabled>Realisasi Pembatalan</button>
      </div>
    </div>
  </div>
</div>

@include('sweetalert::alert')

<script>
  $(function () {
    let selectedRows = [];

    // Initialize DataTable with AJAX
    let table = $('#wakalahTable').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      ajax: "{{ route('pembatalan_wakalah.data') }}",
      columns: [
        {
          data: 'DT_RowIndex',
          name: 'DT_RowIndex',
          orderable: false,
          searchable: false
        },
        {
          data: 'pilih',
          name: 'pilih',
          orderable: false,
          searchable: false
        },
        { data: 'nama_kelompok', name: 'nama_kelompok' },
        { data: 'nama', name: 'nama' },
        { data: 'pembiayaan', name: 'pembiayaan', },
        { data: 'margin', name: 'margin', },
        { data: 'tgl_murabahah', name: 'tgl_murabahah' },
        { data: 'tgl_jatuh_tempo', name: 'tgl_jatuh_tempo' }
      ],
      language: {
        emptyTable: "Belum ada data"
      },
      drawCallback: function(settings) {
        if (settings.aoData.length === 0) {
          $('#wakalahTable tbody').html('<tr><td colspan="8" class="text-center">Belum ada data</td></tr>');
        }
      }
    });

    // Handle checkbox selection
    $('#wakalahTable').on('change', '.select-row', function () {
      const id = $(this).data('id');

      if ($(this).is(':checked')) {
        selectedRows.push(id);
      } else {
        selectedRows = selectedRows.filter(rowId => rowId !== id);
      }

      // Enable or disable the realisasi button based on selection
      $('#realisasiBtn').prop('disabled', selectedRows.length === 0);
    });

    // Handle realisasi button click
    $('#realisasiBtn').on('click', function () {
      let userId = $('#userId').val();
      let userDate = $('#userDate').val();

      if (selectedRows.length > 0) {
        Swal.fire({
          title: 'Konfirmasi',
          text: 'Apakah Anda yakin ingin melakukan pembatalan wakalah untuk item yang dipilih?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, Lanjutkan',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "{{ route('pembatalan_wakalah.realisasi') }}",
              type: "POST",
              data: {
                cifs: selectedRows,
                id: userId,
                param_tanggal: userDate,
                _token: "{{ csrf_token() }}"
              },
              success: function (response) {
                if (response.success) {
                  Swal.fire({
                    title: 'Berhasil!',
                    text: response.message,
                    icon: 'success'
                  }).then(() => {
                    table.ajax.reload();
                    selectedRows = [];
                    $('#realisasiBtn').prop('disabled', true);
                  });
                }
              },
              error: function (error) {
                Swal.fire({
                  title: 'Error!',
                  text: 'Terjadi kesalahan saat memproses data',
                  icon: 'error'
                });
              }
            });
          }
        });
      }
    });
  });
</script>

@endsection