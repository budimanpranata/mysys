@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Restrukturisasi Kemampuan Bayar</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Rest Kemampuan Bayar</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container mt-4">
        <h3 class="mb-4">Rest Kemampuan Bayar</h3>
        <div class="card shadow rounded-3 p-4">
            <div class="row g-3">
                <div class="col-md-12">
                    <label for="kodeKelompok" class="form-label fw-semibold">Kode Kelompok</label>
                    <select class="form-control select2bs3" id="kodeKelompok">
                        <option value="">Cari kode kelompok...</option>
                    </select>
                </div>


                <div class="col-12 text-end mt-3">
                    <button type="button" class="btn btn-primary px-5 py-2" id="btnSearch">
                        <i class="fas fa-search me-2"></i> Cari
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="loading" class="text-center my-4" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="card-body">
        <form method="POST">
            @csrf
            <div class="container mt-4">
                <div class="card p-4 mt-0 shadow">
                    <table class="table table-bordered text-center">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Pilih</th>
                                <th>Nama Kelompok</th>
                                <th>Nama</th>
                                <th>Pembiayaan</th>
                                <th>Margin</th>
                                <th>Tgl Murabahah</th>
                                <th>Setoran</th>
                                <th>Kemampuan Bayar</th>
                            </tr>
                        </thead>
                        <tbody id="dataTable">
                            <tr>
                                <td colspan="9">Tidak ada data.</td>
                            </tr>
                        </tbody>
                        <tfoot id="tableFooter" style="display: none;">
                            <tr class="fw-bold bg-light">
                                <td colspan="7" class="text-end">Total</td>
                                <td id="totalSetoran">Rp 0</td>
                                <td id="totalKemampuanBayar">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="text-end">
                        <button type="button" class="btn btn-success btn-realisasi">
                            <i class="fas fa-check-circle me-2"></i> Realisasi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @include('sweetalert::alert')

    <script>
        $(document).ready(function() {
            $('#kodeKelompok').select2({
                theme: 'bootstrap3',
                placeholder: 'Cari kode kelompok...',
                allowClear: false,
                ajax: {
                    url: '/rest-kemampuan-bayar-get-kelompok',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(item => ({
                                id: item.code_kel,
                                text: item.code_kel + ' - ' + item.nama_kel
                            }))
                        };
                    },
                    cache: true
                }
            });

            $('#btnSearch').on('click', function() {
                const kodeKelompok = $('#kodeKelompok').val().trim();


                if (!kodeKelompok) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Data tidak lengkap',
                        text: 'Pilih kode kelompokuntuk realisasi!'
                    });
                    return;
                }

                $('#loading').show();
                $('#dataTable').html('<tr><td colspan="9">Memuat data...</td></tr>');
                $('#tableFooter').hide();

                $.ajax({
                    url: 'rest-kemampuan-bayar/getData',
                    type: 'GET',
                    data: {
                        kode_kelompok: kodeKelompok,

                    },
                    success: function(data) {
                        let rows = '';
                        let totalSetoran = 0;
                        let totalKemampuan = 0;

                        if (data.length > 0) {
                            data.forEach((item, index) => {
                                const kemampuanBayar = item.kemampuan_bayar ?? 0;
                                totalSetoran += parseFloat(item.bulat);
                                totalKemampuan += parseFloat(kemampuanBayar);

                                rows += `
                            <tr data-id="${item.cif}">
                                <td>${index + 1}</td>
                                <td><input type="checkbox" data-id="${item.cif}" checked></td>
                                <td>${item.nama_kel}</td>
                                <td>${item.nama}</td>
                                <td>${new Intl.NumberFormat('id-ID').format(item.plafond)}</td>
                                <td>${new Intl.NumberFormat('id-ID').format(item.saldo_margin)}</td>
                                <td>${item.tgl_murab}</td>
                                <td>${new Intl.NumberFormat('id-ID').format(item.bulat)}</td>
                                <td>
                                    <input type="number" name="kemampuan_bayar[${item.cif}]"
                                           value="${kemampuanBayar}"
                                           class="form-control kemampuan-input" />
                                </td>
                            </tr>
                        `;
                            });

                            $('#tableFooter').show();
                            $('#totalSetoran').text(
                                `Rp ${new Intl.NumberFormat('id-ID').format(totalSetoran)}`);
                            $('#totalKemampuanBayar').text(
                                `Rp ${new Intl.NumberFormat('id-ID').format(totalKemampuan)}`
                            );
                        } else {
                            rows = '<tr><td colspan="9">Tidak ada data ditemukan.</td></tr>';
                        }

                        $('#dataTable').html(rows);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal mengambil data',
                            text: 'Coba lagi dengan kelompok dan tanggal berbeda!',
                        });
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            });

            $(document).on('input', '.kemampuan-input', function() {
                let totalKemampuan = 0;
                $('.kemampuan-input').each(function() {
                    const val = parseFloat($(this).val()) || 0;
                    totalKemampuan += val;
                });
                $('#totalKemampuanBayar').text(
                    `Rp ${new Intl.NumberFormat('id-ID').format(totalKemampuan)}`);
            });

            $(document).on('click', '.btn-realisasi', function(e) {
                e.preventDefault();
                const selectedIds = [];
                $('input[type="checkbox"]:checked').each(function() {
                    const id = $(this).data('id');
                    if (id) selectedIds.push(id);
                });

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tidak ada yang dipilih',
                        text: 'Pilih minimal satu data untuk direalisasi.',
                    });
                    return;
                }

                const kemampuanBayar = {};
                selectedIds.forEach(id => {
                    const val = $(`input[name="kemampuan_bayar[${id}]"]`).val();
                    kemampuanBayar[id] = val;
                });

                Swal.fire({
                    title: 'Yakin ingin realisasi?',
                    text: 'Proses ini tidak bisa dibatalkan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Realisasi!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/proses-rest-kemampuan-bayar',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                ids: selectedIds,
                                kemampuan_bayar: kemampuanBayar
                            },
                            success: function(response) {
                                Swal.fire('Berhasil!', response.message, 'success')
                                    .then(() => {
                                        $('#kodeKelompok').val('').trigger(
                                            'change');

                                        location.reload();
                                    });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: xhr.responseJSON?.message ||
                                        'Terjadi kesalahan.',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
