@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Bukti Setor</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Bukti Setor</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Setoran Bank Mobile Collection</h3>
            </div>
            <div class="card-body">
                <table id="setoranTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Tgl Setor</th>
                            <th>Unit</th>
                            <th>AO</th>
                            <th>Bank</th>
                            <th>Nominal</th>
                            <th>Bukti Setor</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#setoranTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{{ route('setoran-bank.data') }}",
                columns: [{
                        data: 'tgl_setor'
                    },
                    {
                        data: 'kode_unit'
                    },
                    {
                        data: 'nama_ao'
                    },
                    {
                        data: 'bank'
                    },
                    {
                        data: 'name',
                        render: $.fn.dataTable.render.number(',', '.', 0)
                    },
                    {
                        data: 'image',
                        render: function(data, type, row) {
                            if (data) {
                                return `<a href="http://185.201.9.210/apimobcol/uploads/${data}" class="btn btn-info btn-sm" target="_blank">
                                    <i class="fa fa-download"></i> Download
                                </a>`;
                            } else {
                                return '-';
                            }
                        }
                    }
                ]
            });
        });
    </script>
@endpush
