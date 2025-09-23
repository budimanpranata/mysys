@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Data Transaksi CS Mobile Collection</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Unit</th>
                        <th>Tgl Tagih</th>
                        <th>Kelompok</th>
                        <th>CIF</th>
                        <th>Nama</th>
                        <th>Tagihan</th>
                        <th>Nyata</th>
                        <th>Status Transaksi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="9" class="text-center">No data available in table</td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-3">
                <a href="{{ route('export.cs') }}" class="btn btn-info">Excel Cs</a>
                <a href="{{ route('export.penarikan') }}" class="btn btn-info">Excel Penarikan</a>
                <a href="{{ route('export.lebaran') }}" class="btn btn-info">Excel Lebaran</a>
                <a href="{{ route('export.lima') }}" class="btn btn-info">Excel Lima%</a>
                <a href="{{ route('export.pelunasan') }}" class="btn btn-info">Excel Pelunasan</a>
                <a href="{{ route('export.tunggakan') }}" class="btn btn-info">Excel Tunggakan</a>
                <a href="{{ route('export.wo') }}" class="btn btn-info">Excel WO</a>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tagihanTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{{ route('csmobcol.data') }}",
                columns: [{
                        data: 'unit'
                    },
                    {
                        data: 'tgl_tagih'
                    },
                    {
                        data: 'nama_kel'
                    },
                    {
                        data: 'cif'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'bulat'
                    },
                    {
                        data: 'nominal'
                    },
                    {
                        data: 'status_trans'
                    }
                ]
            });
        });
    </script>
@endpush
