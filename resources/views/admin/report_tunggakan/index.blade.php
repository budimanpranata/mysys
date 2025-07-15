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
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div>
                        <a href="{{ route('reportTunggakan.export') }}" class="btn btn-sm btn-success ml-auto"><i class="fas fa-file-export"></i> Export Excel</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Unit</th>
                                <th>Nama Kelompok</th>
                                <th>Nama AO</th>
                                <th>CIF</th>
                                <th>Nama</th>
                                <th>FT</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

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
                    url: '{{ route('reportTunggakan.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'unit'
                    },
                    {
                        data: 'nama_kelompok'
                    },
                    {
                        data: 'nama_ao'
                    },
                    {
                        data: 'cif'
                    },
                    {
                        data: 'nama_anggota'
                    },
                    {
                        data: 'ft'
                    },
                    {
                        data: 'total_tunggakan'
                    },
                ]
            })
        });

    </script>
@endpush
