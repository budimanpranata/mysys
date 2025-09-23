@extends('layouts.main')

@section('content-header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ $title }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Report Mobcol</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">

        {{-- Filter Tanggal --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('report.index') }}" class="form-inline">
                    <label class="mr-2 font-weight-bold">Tanggal Report</label>
                    <input type="date" name="tanggal" value="{{ $tanggal ?? date('Y-m-d') }}" class="form-control mr-2">
                    <button type="submit" class="btn btn-success">Cari</button>
                </form>
            </div>
        </div>

        {{-- Report CS --}}
        <div class="report-cs" style="{{ request()->has('tanggal') ? '' : 'display:none;' }}">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="font-weight-bold">Report CS</h5>
                    <a href="{{ route('report.export', ['type' => 'cs', 'tanggal' => $tanggal]) }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> CS
                    </a>

                    <a href="{{ route('report.export', ['type' => 'musyarakah', 'tanggal' => $tanggal]) }}"
                        class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Musyarakah
                    </a>
                </div>
            </div>
        </div>

        {{-- Report Non-CS --}}
        <div class="report-non-cs" style="{{ request()->has('tanggal') ? '' : 'display:none;' }}">
            <div class="card">
                <div class="card-body">
                    <h5 class="font-weight-bold">Report Non CS</h5>
                    <a href="{{ route('report.export', ['type' => 'penarikan', 'tanggal' => $tanggal]) }}"
                        class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Penarikan
                    </a>

                    <a href="{{ route('report.export', ['type' => 'lima', 'tanggal' => $tanggal]) }}"
                        class="btn btn-success">
                        <i class="fas fa-file-excel"></i> 5%
                    </a>

                    <a href="{{ route('report.export', ['type' => 'wo', 'tanggal' => $tanggal]) }}"
                        class="btn btn-success">
                        <i class="fas fa-file-excel"></i> WO
                    </a>

                    <a href="{{ route('report.export', ['type' => 'pelunasan', 'tanggal' => $tanggal]) }}"
                        class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Pelunasan
                    </a>

                    <a href="{{ route('report.export', ['type' => 'lebaran', 'tanggal' => $tanggal]) }}"
                        class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Lebaran
                    </a>

                    <a href="{{ route('report.export', ['type' => 'tunggakan', 'tanggal' => $tanggal]) }}"
                        class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Tunggakan
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Kalau mau pakai tanpa reload (AJAX style), aktifkan ini:
            // $('form').on('submit', function(e) {
            //     e.preventDefault();
            //     $('.report-cs').show();
            //     $('.report-non-cs').show();
            // });
        });
    </script>
@endpush
