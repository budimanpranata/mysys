@extends('layouts.main')

@section('content-header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ $title }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <form id="" method="POST" action="">
                        @csrf

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Status Nominative</label>
                            <div class="col-sm-6">
                                <select name="status_nominative" class="form-control" id="status_nominative">
                                    <option>-- Pilih Status Nominative --</option>
                                    <option value="current">Current</option>
                                    <option value="eom">EOM</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Bulan dan Tahun Pencairan</label>
                            <div class="col-sm-3">
                                <select name="bulan" class="form-control" id="bulan">
                                    <option value="">-- Pilih Bulan --</option>
                                    <option value="januari">Januari</option>
                                    <option value="februari">Februari</option>
                                    <option value="maret">Maret</option>
                                    <option value="april">April</option>
                                    <option value="mei">Mei</option>
                                    <option value="juni">Juni</option>
                                    <option value="juli">Juli</option>
                                    <option value="agustus">Agustus</option>
                                    <option value="september">September</option>
                                    <option value="oktober">Oktober</option>
                                    <option value="november">November</option>
                                    <option value="desember">Desember</option>
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <select name="tahun" class="form-control" id="tahun">
                                    <option>-- Pilih Tahun --</option>
                                    {{-- <option value="2025">2025</option> --}}
                                    @for ($y = date('Y'); $y >= 2012; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6 offset-sm-2">
                                <button type="button" class="btn btn-primary" id="filterButton">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div>
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>UNIT</th>
                                    <th>TOTAL NOA</th>
                                    <th>TOTAL SALDO</th>
                                    <th>EXPORT</th>
                                </tr>
                            </thead>
                            <tbody id="data-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#filterButton').on('click', function () {
        let status = $('#status_nominative').val();
        let bulan = $('#bulan').val();
        let tahun = $('#tahun').val();

        let btn = $(this);
        btn.prop('disabled', true);
        let originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        $.ajax({
            url: "{{ route('nominativePembiayaan.getData') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                status_nominative: status,
                bulan: bulan,
                tahun: tahun
            },
            success: function (response) {
                let tbody = '';
                if (response.data.length > 0) {
                    $.each(response.data, function (i, item) {
                        tbody += `<tr>
                            <td>${item.unit}</td>
                            <td>${item.total_noa}</td>
                            <td>${parseInt(item.total_saldo).toLocaleString('id-ID')}</td>
                            <td>
                                <form action="{{ route('nominativePembiayaan.export') }}" method="GET">
                                    <input type="hidden" name="status_nominative" value="${status}">
                                    <input type="hidden" name="bulan" value="${bulan}">
                                    <input type="hidden" name="tahun" value="${tahun}">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-file-export"></i> Export
                                    </button>
                                </form>
                            </td>
                        </tr>`;
                    });
                } else {
                    tbody = `<tr><td colspan="5" class="text-center">Tidak ada data</td></tr>`;
                }
                $('#data-table-body').html(tbody);
            },
            error: function () {
                alert('Terjadi kesalahan saat mengambil data!');
            },
            complete: function () {
                btn.prop('disabled', false);
                btn.html(originalText);
            }
        });
    });
});
</script>
@endpush
