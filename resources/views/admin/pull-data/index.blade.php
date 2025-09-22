@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pull Data</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Pull Data</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="card mb-4">
            <div class="card-body">
                <form id="formPullData" action="{{ url('/pull-data/proses') }}" method="POST">
                    @csrf

                    <div class="form-group
                    row">
                        <label class="col-sm-3 col-form-label">Jenis Pull Data</label>
                        <div class="col-sm-9">
                            <select name="jenis_pull" class="form-control" required>
                                <option value="">-- jenis pull data --</option>
                                <option value="01">Kelompok</option>
                                <option value="02">Perorang</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row position-relative">
                        <label class="col-sm-3 col-form-label">Kode Kelompok/CIF</label>
                        <div class="col-sm-9 d-flex">
                            <input type="text" name="kode_kelompok" id="kode_kelompok" class="form-control mr-2"
                                placeholder="Ketik CIF / Kode Kelompok">
                            <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama">
                            <!-- suggestion box -->
                            <div id="suggestion-box" class="list-group position-absolute w-50"
                                style="z-index: 1000; max-height:200px; overflow:auto; display:none; top:100%;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jenis Transaksi</label>
                        <div class="col-sm-9">
                            <select name="jenis_transaksi" id="jenis_transaksi" class="form-control" required>
                                <option value="">-- pilih jenis transaksi --</option>
                                <option value="lima">5%</option>
                                <option value="lebaran">Lebaran</option>
                                <option value="pelunasan">Pelunasan</option>
                                <option value="pelunasan19">Pelunasan Ke 19</option>
                                <option value="pelunasanRestMargin">Pelunasan Rest Pokok dan Margin</option>
                                <option value="penarikan">Penarikan</option>
                            </select>
                        </div>
                    </div>
                    <div class="nilai">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nominal</label>
                            <div class="col-sm-9">
                                <input type="text" id = "nominal" name="nominal" class="form-control" required>
                            </div>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Tagih</label>
                        <div class="col-sm-9">
                            <input type="date" name="tanggal_tagih" class="form-control" required>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Proses</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel hasil -->
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped" id="tableData">
                    <thead>
                        <tr>
                            <th>CIF</th>
                            <th>Nama</th>
                            <th>Tanggal Transaksi</th>
                            <th>Jenis Transaksi</th>
                            <th>Nominal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse ($data as $row)
                            <tr>
                                <td>{{ $row->cif }}</td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->tgl_tagih ?? '-' }}</td>
                                <td>{{ $row->jenis_pull ?? '-' }}</td>
                                <td>{{ number_format($row->bayar ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm btnHapus" data-id="{{ $row->id }}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            // autosuggest
            $("#kode_kelompok").on("keyup", function() {
                let query = $(this).val();
                if (query.length > 1) {
                    $.ajax({
                        url: "{{ route('pull-data.suggest') }}",
                        type: "GET",
                        data: {
                            q: query
                        },
                        success: function(data) {
                            let box = $("#suggestion-box");
                            box.empty().show();

                            if (data.length > 0) {
                                $.each(data, function(i, item) {
                                    box.append(`
                                    <a href="#" class="list-group-item list-group-item-action"
                                       data-cif="${item.cif}" data-nama="${item.nama}" data-namakel="${item.nama_kel}">
                                       ${item.cif} - ${item.nama} (${item.nama_kel})
                                    </a>
                                `);
                                });
                            } else {
                                box.append(
                                    '<div class="list-group-item">Tidak ada hasil</div>');
                            }
                        }
                    });
                } else {
                    $("#suggestion-box").hide();
                }
            });

            // klik pilihan
            $(document).on("click", "#suggestion-box a", function(e) {
                e.preventDefault();
                $("#kode_kelompok").val($(this).data("cif"));
                $("#nama").val($(this).data("nama"));
                $("#suggestion-box").hide();
            });

            // submit form
            $('#formPullData').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "/pull-data/proses",
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let tbody = $("#tableBody");
                        tbody.empty();

                        if (response.data && response.data.length > 0) {
                            $.each(response.data, function(i, item) {
                                tbody.append(`
                                <tr>
                                    <td>${item.cif}</td>
                                    <td>${item.nama}</td>
                                    <td>${item.tgl_tagih ?? '-'}</td>
                                    <td>${item.jenis_pull ?? '-'}</td>
                                    <td>${item.bulat ? parseInt(item.bulat).toLocaleString() : 0}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm btnHapus" data-id="${item.id}">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            `);
                            });
                            Swal.fire({
                                icon: "success",
                                title: "Data berhasil diproses",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            tbody.append(`
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data ditemukan</td>
                            </tr>
                        `);
                            Swal.fire("Info", "Tidak ada data ditemukan", "info");
                        }
                    },
                    error: function(xhr) {
                        Swal.fire("Error", "Terjadi kesalahan: " + xhr.status, "error");
                    }
                });
            });

            // hapus data
            $(document).on("click", ".btnHapus", function() {
                let id = $(this).data("id");

                Swal.fire({
                    title: "Yakin hapus data ini?",
                    text: "Data yang dihapus tidak bisa dikembalikan.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/pull-data/" + id,
                            type: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: res.message,
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                // reload data tabel
                                $.ajax({
                                    url: "/pull-data/list",
                                    type: "GET",
                                    success: function(response) {
                                        let tbody = $("#tableBody");
                                        tbody.empty();

                                        if (response.data && response.data
                                            .length > 0) {
                                            $.each(response.data, function(
                                                i, item) {
                                                tbody.append(`
                                                <tr>
                                                    <td>${item.cif}</td>
                                                    <td>${item.nama}</td>
                                                    <td>${item.tgl_tagih ?? '-'}</td>
                                                    <td>${item.jenis_pull ?? '-'}</td>
                                                    <td>${item.bayar ? parseInt(item.bayar).toLocaleString() : 0}</td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm btnHapus" data-id="${item.id}">
                                                            Hapus
                                                        </button>
                                                    </td>
                                                </tr>
                                            `);
                                            });
                                        } else {
                                            tbody.append(`
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada data ditemukan</td>
                                            </tr>
                                        `);
                                        }
                                    }
                                });
                            },
                            error: function(xhr) {
                                Swal.fire("Error", "Gagal hapus: " + xhr.status,
                                    "error");
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.nilai').hide();
            $('#nominal').prop('required', false); // awal tidak wajib

            $('#jenis_transaksi').change(function() {
                if ($(this).val() === 'penarikan') {
                    $('.nilai').show();
                    $('#nominal').prop('required', true); // wajib diisi
                } else {
                    $('.nilai').hide();
                    $('#nominal').val('');
                    $('#nominal').prop('required', false); // tidak wajib
                }
            });
        });
    </script>
@endpush
