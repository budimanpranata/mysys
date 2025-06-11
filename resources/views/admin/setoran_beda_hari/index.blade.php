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
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
            border: 1px solid #ced4da;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="code_kel" class="col-sm-2 col-form-label">Kode Kelompok</label>
                        <div class="col-sm-6">
                            <select class="form-control select2-ajax" id="code_kel" style="width: 100%;">
                                <!-- Opsi akan di-load via AJAX -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6 offset-sm-2">
                            <button id="filterButton" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Hasil Pencarian -->
            <div class="card">
                <div class="card-body">
                    <div id="result">
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>UNIT</th>
                                    <th>KELOMPOK</th>
                                    <th>NOA</th>
                                    <th>NAMA AO</th>
                                    <th>SETORAN</th>
                                    <th>TGL</th>
                                    <th>AKSI</th>
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
        $(document).ready(function() {
            // Inisialisasi Select2 dengan AJAX
            $('.select2-ajax').select2({
                placeholder: 'Cari Kode Kelompok...',
                // minimumInputLength: 3, // Minimal karakter untuk mulai pencarian
                ajax: {
                    url: "{{ route('cari.kelompok') }}", // Route untuk cari kelompok
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            cari: params.term // Parameter search
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.code_kel,
                                    text: item.code_kel + ' - ' + item.nama_kel
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });

        $(document).ready(function () {
            $('#filterButton').click(function () {
                // Ambil nilai input
                var code_kel = $('#code_kel').val();
                
                // Validasi input
                if (code_kel === '') {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Kode Kelompok harus diisi!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                
                // Tampilkan loading
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Silakan tunggu',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // AJAX request
                $.ajax({
                    url: "{{ route('setoranBedaHari.filter') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        code_kel: code_kel,
                    },
                    success: function (response) {
                        Swal.close();
                        var tbody = $('table tbody');
                        tbody.empty();
                        
                        function formatRupiah(angka) {
                            return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(angka);
                        }
                        
                        if (response.anggota && response.anggota.length > 0) {                            
                            // Data anggota
                            $.each(response.anggota, function(index, anggota) {
                                tbody.append(`
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${anggota.no}</td>
                                        <td>${anggota.nama}</td>
                                        <td>${anggota.alamat}</td>
                                        <td class="text-right">${formatRupiah(anggota.angsuran)}</td>
                                        <td class="text-right">${formatRupiah(anggota.saldo)}</td>
                                        <td>${anggota.ke}/${anggota.run_tenor}</td>
                                    </tr>
                                `);
                            });

                            // SweetAlert jika data ditemukan
                            Swal.fire({
                                title: 'Data Ditemukan!',
                                text: 'Data kelompok berhasil dimuat.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                            
                        } else {
                            $('#kelompok-info').hide();
                            tbody.append('<tr><td colspan="8" class="text-center">Tidak ada data anggota</td></tr>');
                        }
                    },
                    error: function (xhr) {
                        Swal.close();
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Gagal mengambil data',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        console.error(xhr.responseText);
                    }
                });
            });
        });
        
    </script>
@endpush