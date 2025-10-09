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
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <form id="filterForm">
                        @csrf

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Tanggal Cetak/Export</label>
                            <div class="col-sm-6">
                                <input type="date" name="tanggal_cetak" id="tanggal_cetak" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jenis Kolektibilitas</label>
                            <div class="col-sm-6">
                                <select name="jenis_kolek" id="jenis_kolek" class="form-control" required>
                                    <option value="">-- Pilih Jenis Kolektibilitas --</option>
                                    <option value="semua">Semua</option>
                                    <option value="lancar">Lancar</option>
                                    <option value="kurang_lancar">Kurang Lancar</option>
                                    <option value="diragukan">Diragukan</option>
                                    <option value="macet">Macet</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6 offset-sm-2">
                                <button type="submit" class="btn btn-primary" id="filterButton">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="resultTable">
                            <thead class="bg-light">
                                <tr>
                                    <th>Unit</th>
                                    <th>Total NoA</th>
                                    <th>Plafond</th>
                                    <th>Saldo Pinjaman</th>
                                    <th>Rekap</th>
                                    <th>Excel</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center">Silakan pilih jenis kolektibilitas untuk menampilkan data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                
                const formData = {
                    jenis_kolek: $('#jenis_kolek').val(),
                    _token: $('input[name="_token"]').val()
                };

                $.ajax({
                    url: '{{ route("report.ppap.cari") }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#filterButton').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memuat...');
                        $('#resultTable tbody').html('<tr><td colspan="6" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>');
                    },
                    success: function(response) {
                        if(response.success) {
                            let tbody = '';
                            
                            if(response.data.length > 0) {
                                response.data.forEach(function(item) {
                                    tbody += `
                                        <tr>
                                            <td>${item.unit}</td>
                                            <td class="text-center">${item.total_noa}</td>
                                            <td class="text-right">Rp ${formatRupiah(item.plafond)}</td>
                                            <td class="text-right">Rp ${formatRupiah(item.os)}</td>
                                            <td class="text-center">
                                                <a href="{{ route('report.ppap.export.pdf') }}?tanggal_cetak=${$('#tanggal_cetak').val()}&jenis_kolek=${$('#jenis_kolek').val()}&unit=${item.unit}" 
                                                   class="btn btn-sm btn-danger" target="_blank">
                                                    <i class="fas fa-file-pdf"></i> PDF
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('report.ppap.export.excel') }}?tanggal_cetak=${$('#tanggal_cetak').val()}&jenis_kolek=${$('#jenis_kolek').val()}&unit=${item.unit}" 
                                                   class="btn btn-sm btn-success">
                                                    <i class="fas fa-file-excel"></i> Excel
                                                </a>
                                            </td>
                                        </tr>
                                    `;
                                });
                            } else {
                                tbody = '<tr><td colspan="6" class="text-center">Data tidak ditemukan</td></tr>';
                            }
                            
                            $('#resultTable tbody').html(tbody);
                        }
                    },
                    error: function(xhr) {
                        $('#resultTable tbody').html('<tr><td colspan="6" class="text-center text-danger">Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Silakan coba lagi') + '</td></tr>');
                    },
                    complete: function() {
                        $('#filterButton').prop('disabled', false).html('<i class="fas fa-search"></i> Cari');
                    }
                });
            });

            function formatRupiah(angka) {
                if (!angka) return '0';
                return parseInt(angka).toLocaleString('id-ID');
            }
        });
    </script>
@endpush