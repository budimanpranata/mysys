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
                    <a href="{{ route('anggota.create') }}" class="btn btn-sm btn-primary">+ Tambah</a>
                    <a href="{{ route('anggota.export') }}" class="btn btn-sm btn-success">Export Excel</a>
                    <button onclick="addForm('{{ route('kelompok.store') }}')" class="btn btn-sm btn-primary">+ Tambah Kelompok</button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Anggota</th>
                                <th>CIF</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>TTL</th>
                                <th>AP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

@include('admin.master_kelompok.form')

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <script>

        let table;

        $(function() {
            table = $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('anggota.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'no'
                    },
                    {
                        data: 'cif'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'alamat'
                    },
                    {
                        data: null, // Gabungkan field tempat_lahir dan tgl_lahir
                        render: function(data, type, row) {
                            return `${row.tempat_lahir}, ${row.tgl_lahir}`;
                        }
                    },
                    {
                        data: 'unit'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ]
            })
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.ajax({
                    url: $('#modal-form form').attr('action'),
                    type: 'post',
                    data: $('#modal-form form').serialize()
                })
                .done((response) => {
                    Swal.fire({
                        title: 'Data berhasil diinput!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                    $('#modal-form').modal('hide');
                    table.ajax.reload();
                })
                .fail((errors) => {
                    Swal.fire({
                        title: 'Tidak dapat menyimpan data!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                })
            }
        });

        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Tambah Kelompok');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=code_kel]').focus();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Produk');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=code_kel]').focus();

            $.get(url)
            .done((response) => {
                $('#modal-form [name=code_kel]').val(response.code_kel);
                $('#modal-form [name=code_unit]').val(response.code_unit);
                $('#modal-form [name=nama_kel]').val(response.nama_kel);
                $('#modal-form [name=alamat]').val(response.alamat);
                $('#modal-form [name=cao]').val(response.cao);
                $('#modal-form [name=cif]').val(response.cif);
                $('#modal-form [name=no_tlp]').val(response.no_tlp);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data.');
                return;
            })
        }

    </script>
@endpush
