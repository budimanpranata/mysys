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
                    <!-- Input Kode Kelompok -->
                    <div class="form-group row">
                        <label for="code_kel" class="col-sm-2 col-form-label">Kode Kelompok</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="code_kel" placeholder="Masukkan Kode Kelompok">
                        </div>
                    </div>
                    <!-- Input Tanggal Akad -->
                    <div class="form-group row">
                        <label for="tgl_akad" class="col-sm-2 col-form-label">Pilih Tanggal Akad</label>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" id="tgl_akad">
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
            <!-- Tabel Hasil Pencarian -->
            <div class="card">
                <div class="card-body">
                    <div id="result">
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>TANGGAL AKAD</th>
                                    <th>CIF</th>
                                    <th>NIK</th>
                                    <th>NAMA ANGGOTA</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <!-- Tombol Cetak PDF -->
                    <div class="mt-3 text-right">
                        <button id="cetakButton" class="btn btn-danger">
                            <i class="fas fa-print"></i> Cetak PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
        $('#filterButton').click(function () {
            // Ambil nilai input
            var code_kel = $('#code_kel').val();
            var tgl_akad = $('#tgl_akad').val();

            // Validasi input
            if (code_kel === '' || tgl_akad === '') {
                alert('Kode Kelompok dan Tanggal Akad harus diisi!');
                return;
            }

            // AJAX request
            $.ajax({
                url: "{{ route('cetakSimpanan5Persen.filter') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    code_kel: code_kel,
                    tgl_akad: tgl_akad
                },
                success: function (response) {
                    var tbody = $('table tbody');
                    tbody.empty(); // Bersihkan tabel

                    if (response.data.length > 0) {
                        // Loop hasil pencarian
                        $.each(response.data, function (index, item) {
                            tbody.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.tgl_akad}</td>
                                    <td>${item.cif}</td>
                                    <td>${item.ktp}</td>
                                    <td>${item.nama_anggota}</td>
                                </tr>
                            `);
                        });
                    } else {
                        tbody.append('<tr><td colspan="5" class="text-center">Data tidak ditemukan</td></tr>');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan, silakan coba lagi.');
                }
            });
        });
    });

        // Tombol Cetak PDF
        document.getElementById('cetakButton').addEventListener('click', function(e) {
            e.preventDefault();

            const kodeKel = document.getElementById('code_kel').value;
            const tglAkad = document.getElementById('tgl_akad').value;

            // Validasi input sebelum cetak
            if (!kodeKel || !tglAkad) {
                alert('Harap masukkan Kode Kelompok dan pilih Tanggal Akad untuk mencetak PDF!');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('cetakSimpanan5Persen.pdf') }}';
            // form.target = '_blank'; // Membuka PDF di tab baru

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';

            const kodeKelInput = document.createElement('input');
            kodeKelInput.type = 'hidden';
            kodeKelInput.name = 'code_kel';
            kodeKelInput.value = kodeKel;

            const tglAkadInput = document.createElement('input');
            tglAkadInput.type = 'hidden';
            tglAkadInput.name = 'tgl_akad';
            tglAkadInput.value = tglAkad;

            form.appendChild(csrfInput);
            form.appendChild(kodeKelInput);
            form.appendChild(tglAkadInput);

            document.body.appendChild(form);
            form.submit();
        });
    </script>
@endpush
