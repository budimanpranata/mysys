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
                    <div class="form-group row">
                        <label for="jenis_pengajuan" class="col-sm-2 col-form-label">Jenis Pengajuan</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="jenis_pengajuan">
                                <option value="" hidden>-- pilih jenis Pengajuan --</option>
                                <option value="pembiayaan">Pembiayaan</option>
                                <option value="turun_plafond">Turun Plafond</option>
                                <option value="ajukan_kembali">Ajukan Kembali</option>
                                <option value="hapus_pengajuan">Hapus Pengajuan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Cari -->
                    <div class="form-group row">
                        <div class="col-sm-6 offset-sm-2">
                            <button id="filterButton" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DATA APPROVE -->
            <div class="row mt-3" id="data-approve-section" style="display: block;">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <strong>DATA APPROVE</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered text-center mb-0">
                                <thead style="background: #f5f5f5;">
                                    <tr>
                                        <th style="width: 70px;">PILIH</th>
                                        <th>KODE KEL</th>
                                        <th>NOREK</th>
                                        <th>CIF</th>
                                        <th>NAMA</th>
                                        <th>PEMBIYAAN</th>
                                        <th>SALDO PEMBIAYAAN</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody id="approveTableBody">
                                    
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-success mr-2" id="btnApproveCheckbox">
                                <i class="fas fa-check"></i> Approve
                            </button>

                            <button class="btn btn-danger" id="btnBatalCheckbox">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>

    $('#jenis_pengajuan').on('change', function () {
        let jenis = $(this).val();

        if (!jenis) return;

            // 👉 redirect khusus turun plafond
        if (jenis === 'turun_plafond') {
            window.location.href = "{{ url('/al/approval-pengajuan/turun-plafond') }}";
            return;
        }

        if (jenis === 'ajukan_kembali') {
            window.location.href = "{{ url('/al/approval-pengajuan/ajukan-kembali') }}";
            return;
        }

        if (jenis === 'hapus_pengajuan') {
            window.location.href = "{{ url('/al/approval-pengajuan/hapus') }}";
            return;
        }

        // pembiayaan → tetap di halaman ini
    });

    $('#filterButton').on('click', function () {
        let jenis = $('#jenis_pengajuan').val();
    
        if (!jenis) {
            alert('Pilih jenis pengajuan terlebih dahulu');
            return;
        }
    
        $.ajax({
            url: "{{ route('approve.get_pengajuan') }}",
            type: "GET",
            data: { jenis: jenis },
            success: function (res) {
                let tbody = $('#approveTableBody');
                tbody.empty();
    
                if (res.data.length === 0) {
                    tbody.append(`
                        <tr>
                            <td colspan="7">Data tidak ditemukan</td>
                        </tr>
                    `);
                    return;
                }

                const rupiah = new Intl.NumberFormat('id-ID');
    
                res.data.forEach(function (item) {
                    tbody.append(`
                        <tr>
                            <td>
                                <input type="checkbox"
                                    class="approve-checkbox"
                                    value="${item.no_anggota}">
                            </td>
                            <td>${item.code_kel}</td>
                            <td>${item.no_anggota}</td>
                            <td>${item.cif}</td>
                            <td>${item.nama}</td>
                            <td>${rupiah.format(item.plafond)}</td>
                            <td>${rupiah.format(item.saldo_margin)}</td>
                            <td>
                                <a href="/al/approval-pengajuan/detail/${item.no_anggota}"
                                    class="btn btn-info btn-sm"> <i class="fas fa-eye"></i>
                                    Detail 
                                </a>
                            </td>
                        </tr>
                    `);
                });
            }
        });
    });

    $('#btnApproveCheckbox').on('click', function () {

        let selected = [];

        $('.approve-checkbox:checked').each(function () {
            selected.push($(this).val());
        });

        if (selected.length === 0) {
            alert('Pilih minimal satu data');
            return;
        }

        if (!confirm('Yakin approve data terpilih?')) return;

        $.ajax({
            url: "{{ url('/al/approval-pengajuan/approve-checkbox') }}",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                no_anggota: selected
            },
            success: function (res) {
                alert(res.message);
                window.location.reload();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                alert('Gagal approve data');
            }
        });
    });

    $('#btnBatalCheckbox').on('click', function () {

        let selected = [];

        $('.approve-checkbox:checked').each(function () {
            selected.push($(this).val());
        });

        if (selected.length === 0) {
            alert('Pilih minimal satu data');
            return;
        }

        if (!confirm('Yakin reject data terpilih?')) return;

        $.ajax({
            url: "{{ url('/al/approval-pengajuan/batal-checkbox') }}",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                no_anggota: selected
            },
            success: function (res) {
                alert(res.message);
                window.location.reload();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                alert('Gagal approve data');
            }
        });
    });

    

    </script>
@endsection

