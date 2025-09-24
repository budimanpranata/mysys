@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Buku Besar</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Buku Besar</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="card mb-4">
            <div class="card-body">
                <form id="formBukuBesar" action="{{ route('buku-besar.proses') }}" method="POST">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jenis Report</label>
                        <div class="col-sm-9">
                            <select name="jenis_pull" class="form-control" required>
                                <option value="">-- Jenis Report --</option>
                                <option value="01">EOM</option>
                                <option value="02">Current</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Bulan & Tahun</label>
                        <div class="col-sm-4">
                            <select name="jenis_transaksi" id="jenis_transaksi" class="form-control">
                                <option value="">-- Bulan --</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select name="tahun" id="tahun" class="form-control">
                                <option value="">-- Tahun --</option>
                                @for ($y = date('Y'); $y >= 2000; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group row position-relative">
                        <label class="col-sm-3 col-form-label">No Perkiraan</label>
                        <div class="col-sm-9 d-flex">
                            <input type="text" name="kode_rekening" id="kode_rekening" class="form-control mr-2"
                                placeholder="Ketik No Perkiraan / Nama">
                            <input type="text" name="nama_rekening" id="nama_rekening" class="form-control"
                                placeholder="Nama" readonly>
                            <div id="suggestion-box" class="list-group position-absolute w-50"
                                style="z-index:1000; max-height:200px; overflow:auto; display:none; top:100%;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jenis Data</label>
                        <div class="col-sm-9">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" value="1" id="all_data"
                                    name="all_data">
                                <label class="form-check-label" for="all_data">All Data</label>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (!empty($data) && count($data) > 0)
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No Perkiraan</th>
                            <th>Nama</th>
                            <th>Saldo Awal</th>
                            <th>Saldo Akhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr>
                                <td>{{ $row->kode_rekening }}</td>
                                <td>{{ $row->nama_rekening }}</td>
                                <td>{{ number_format($row->saldo_awal, 0, ',', '.') }}</td>
                                <td>{{ number_format($row->saldo_akhir, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('buku-besar.download', $row->kode_rekening) }}?tahun={{ $tahun ?? '' }}&bulan={{ $bulan ?? '' }}&all_data={{ $all_data ?? 0 }}"
                                        class="btn btn-success btn-sm">
                                        <i class="fas fa-file-excel"></i> Download
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $("#kode_rekening").on("keyup", function() {
                let query = $(this).val();
                if (query.length > 1) {
                    $.ajax({
                        url: "{{ route('buku-besar.suggest') }}",
                        type: "GET",
                        data: {
                            q: query
                        },
                        success: function(data) {
                            let box = $("#suggestion-box");
                            box.empty();
                            if (data.length > 0) {
                                box.show();
                                data.forEach(function(item) {
                                    box.append(
                                        '<a href="#" class="list-group-item list-group-item-action" ' +
                                        'data-kode="' + item.kode_rekening +
                                        '" ' +
                                        'data-nama="' + item.nama_rekening + '">' +
                                        item.kode_rekening + ' - ' + item
                                        .nama_rekening +
                                        '</a>'
                                    );
                                });
                            } else {
                                box.hide();
                            }
                        }
                    });
                } else {
                    $("#suggestion-box").hide();
                }
            });

            $(document).on("click", "#suggestion-box a", function(e) {
                e.preventDefault();
                $("#kode_rekening").val($(this).data("kode"));
                $("#nama_rekening").val($(this).data("nama"));
                $("#suggestion-box").hide();
            });
        });
    </script>
@endpush
