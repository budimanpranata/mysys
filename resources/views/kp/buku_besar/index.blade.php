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

        {{-- === FORM PROSES BUKU BESAR === --}}
        <div class="card mb-4">
            <div class="card-body">
                <form id="formBukuBesar" action="{{ route('buku-besar-kp.proses') }}" method="POST">
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
                            <select name="bulan" class="form-control" required>
                                <option value="">-- Bulan --</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select name="tahun" class="form-control" required>
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
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-cogs"></i> Proses Export
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- === CARD HASIL EXPORT === --}}
        <div class="card mt-4">
            <div class="card-header bg-light">
                <strong>📦 Hasil Export Buku Besar</strong>
            </div>
            <div class="card-body p-0" id="exportListContainer">
                {{-- konten tabel akan dimuat via AJAX --}}
                <div class="text-center p-3 text-muted">Memuat daftar export...</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {

            // Auto suggest kode rekening
            $("#kode_rekening").on("keyup", function() {
                let query = $(this).val();
                if (query.length > 1) {
                    $.ajax({
                        url: "{{ route('buku-besar-kp.suggest') }}",
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
                                        'data-kode="' + item.kode_rekening + '" ' +
                                        'data-nama="' + item.nama_rekening + '">' +
                                        item.kode_rekening + ' - ' + item
                                        .nama_rekening +
                                        '</a>'
                                    );
                                });
                            } else box.hide();
                        }
                    });
                } else $("#suggestion-box").hide();
            });

            $(document).on("click", "#suggestion-box a", function(e) {
                e.preventDefault();
                $("#kode_rekening").val($(this).data("kode"));
                $("#nama_rekening").val($(this).data("nama"));
                $("#suggestion-box").hide();
            });

            // Submit form AJAX (jalankan job export)
            $('form#formBukuBesar').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);

                Swal.fire({
                    title: 'Mulai Proses?',
                    text: 'File akan diproses di background.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, proses sekarang',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            method: 'POST',
                            data: form.serialize(),
                            success: function(res) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: res.message,
                                    icon: 'success',
                                }).then(() => {
                                    form.trigger('reset'); // reset form
                                    loadExportList(); // refresh daftar export
                                });
                            },
                            error: function(xhr) {
                                Swal.fire('Error',
                                    'Terjadi kesalahan saat memproses export.',
                                    'error');
                            }
                        });
                    }
                });
            });

            // fungsi muat ulang daftar export
            function loadExportList() {
                $('#exportListContainer').html(
                    '<div class="text-center p-3 text-muted"><i class="fas fa-spinner fa-spin"></i> Memuat...</div>'
                );
                fetch('{{ route('buku-besar-kp.export.list') }}')
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('exportListContainer').innerHTML = html;
                    })
                    .catch(() => {
                        $('#exportListContainer').html(
                            '<div class="text-center text-danger p-3">Gagal memuat daftar export.</div>');
                    });
            }

            // auto refresh setiap 10 detik
            setInterval(loadExportList, 10000);
            loadExportList();
        });
    </script>
@endpush
