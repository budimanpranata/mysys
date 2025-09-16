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
                    <form id="transactionForm" method="POST" action="">
                        @csrf

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Tanggal Awal</label>
                            <div class="col-sm-6">
                                <input type="date" class="form-control" id="tanggal_awal">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Tanggal Akhir</label>
                            <div class="col-sm-6">
                                <input type="date" class="form-control" id="tanggal_akhir">
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
                                    <th>NOREK</th>
                                    <th>KETERANGAN</th>
                                    <th>TANGGAL TRANSAKSI</th>
                                    <th>DEBET</th>
                                    <th>KREDIT</th>
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

        function formatTanggal(tanggal) {
            const bulan = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            const d = new Date(tanggal);
            const day = d.getDate();
            const month = bulan[d.getMonth()];
            const year = d.getFullYear();
            return `${day} ${month} ${year}`;
        }

        $('#filterButton').on('click', function () {

            let tanggal_awal = $('#tanggal_awal').val();
            let tanggal_akhir = $('#tanggal_akhir').val();

            $.ajax({
                url: "{{ route('mutasiKas.getTransaksi') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    tanggal_awal: tanggal_awal,
                    tanggal_akhir: tanggal_akhir
                },
                success: function (response) {
                    let rows = '';
                    if (response.data.length > 0) {
                        response.data.forEach(row => {
                            rows += `
                                <tr>
                                    <td>${row.kode_rekening}</td>
                                    <td>${row.keterangan_transaksi}</td>
                                    <td>${formatTanggal(row.tanggal_transaksi)}</td>
                                    <td>${parseFloat(row.debet).toLocaleString()}</td>
                                    <td>${parseFloat(row.kredit).toLocaleString()}</td>
                                    <td>${parseFloat(row.saldo).toLocaleString()}</td>
                                </tr>
                            `;
                        });
                    } else {
                        rows = `<tr><td colspan="6" class="text-center">Data tidak ditemukan</td></tr>`;
                    }

                    $('table tbody').html(rows);
                }
            });
        });
    </script>
@endpush