@extends('layouts.main')

@section('content-header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{ $title }}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="#">Dahsboard</a></li>
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
        </table>
      </div>
    </div>
  </div>
</div>

@include('admin.master_pembiayaan.form-add')

<script>
  let table;

  $(function () {
    table = $('.table').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      autoWidth: false,
      ajax: {
        url: "{{ route('pembiayaan.data') }}"
      },
      columns: [
        { data: 'DT_RowIndex', searchable: false, sortable: false },
        { data: 'nama_kelompok' },
        { data: 'kode_kel' },
        { data: 'no_anggota' },
        { data: 'cif' },
        { data: 'nama_anggota' },
        { data: 'plafond' },
        { data: 'os' },
        { data: 'tenor' },
        { data: 'aksi', searchable: false, sortable: false },
      ]
    });

    $('#add-form').validator().on('submit', function (e) {
      if (!e.preventDefault()) {
        let formData = {
          _token: "{{ csrf_token() }}",
          unit: $('#unit').val(),
          produk: $('#produk').val(),
          no_rek: $('#no_rek').val(),
          cif: $('#cif').val(),
          pengajuan: $('#pengajuan').val(),
          tenor: $('#tenor').val(),
          disetujui: $('#disetujui').val(),
          tgl_wakalah: $('#tgl_wakalah').val(),
          tgl_akad: $('#tgl_akad').val(),
          bidang_usaha: $('#bidang_usaha').val(),
          keterangan_usaha: $('#keterangan_usaha').val(),
          id: "{{ Auth::user()->role_id }}",
          param_tanggal: "{{ Auth::user()->param_tanggal }}",
          cao: $('#cao').val(),
          code_kel: $('#code_kel').val(),
          nama: $('#nama').val(),
          tgl_lahir: $('#tgl_lahir').val(),
          suffix: $('#suffix').val(),
        };

        $.ajax({
          url: $('#add-form form').attr('action'),
          type: 'POST',
          data: formData,
          success: function (response) {
            if (response.status === 'warning') {
              // Handle warning response
              Swal.fire({
                title: 'Perhatian!',
                text: response.message,
                icon: 'warning',
                confirmButtonText: 'OK'
              }).then(() => {
                $('#add-form').modal('hide');

                table.ajax.reload();
              });
            } else {
              // Handle success response
              Swal.fire({
                title: 'Data berhasil disimpan!',
                icon: 'success',
                confirmButtonText: 'OK'
              }).then(() => {
                $('#add-form').modal('hide');

                table.ajax.reload();
              });
            }
          },
          error: function (xhr) {
            // Parse the error response to get the message
            let errorMessage = 'Tidak dapat menyimpan data!';

            try {
              const response = JSON.parse(xhr.responseText);
              if (response.message) {
                errorMessage = response.message;
              }
            } catch (e) {
              console.error('Error parsing response:', e);
            }

            // Display error message
            Swal.fire({
              title: 'Error!',
              text: errorMessage,
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        });
      }
    });
  });

  function addForm(url, cif, no_anggota, unit, cao, code_kel, nama, tgl_lahir, suffix) {
    $('#add-form').modal('show');
    $('#add-form .modal-title').text('Pengajuan Pembiayaan');

    $('#add-form form')[0].reset();
    $('#add-form form').attr('action', url);
    $('#add-form [name=_method]').val('post');

    // autofill field (readonly/hidden)
    $('#cif').val(cif);
    $('#no_rek').val(no_anggota);
    $('#unit').val(unit);
    $('#cao').val(cao);
    $('#code_kel').val(code_kel);
    $('#nama').val(nama);
    $('#tgl_lahir').val(tgl_lahir);
    $('#suffix').val(suffix);

    $('#modal-form [name=produk]').focus();
  }
</script>
@endsection