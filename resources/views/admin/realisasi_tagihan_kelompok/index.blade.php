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
                                    <th>PILIH</th>
                                    <th>NO</th>
                                    <th>CIF</th>
                                    <th>NAMA</th>
                                    <th>UNIT</th>
                                    <th>PEMBIAYAAN</th>
                                    <th>MARGIN</th>
                                    <th>SETOTAN</th>
                                    <th>ANGSURAN</th>
                                    <th>TWM</th>
                                    <th>SALDO TWM</th>
                                    <th>SISA TWM</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <!-- Tombol Cetak PDF -->
                    <div class="mt-3 text-right">
                        <button id="realisasi" class="btn btn-success">
                            <i class="fas fa-print"></i> Realisasi
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
        $(document).ready(function() {
            $('#filterButton').click(function() {
                var code_kel = $('#code_kel').val();

                if (code_kel === '') {
                    Swal.fire({
                            title: 'Peringatan!',
                            text: 'Kode Kelompok harus diisi!',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    return;
                }

                // Tampilkan loading sebelum request AJAX
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
                    url: "{{ route('realisasi.tagihanKelompok.getKelompok') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        code_kel: code_kel,
                    },
                    success: function (response) {
                        Swal.close(); // Tutup loading
                        var tbody = $('table tbody');
                        tbody.empty(); // Bersihkan tabel

                        if (response.data.length > 0) {
                            // Loop hasil pencarian
                            $.each(response.data, function (index, item) {
                                tbody.append(`
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="realisasi-checkbox" 
                                                data-cif="${item.cif}">
                                        </td>
                                        <td>${index + 1}</td>
                                        <td>${item.cif}</td>
                                        <td>${item.nama_anggota}</td>
                                        <td>${item.unit}</td>
                                        <td>${item.os}</td>
                                        <td>${item.saldo_margin}</td>
                                        <td>${item.bulat}</td>
                                        <td>${item.angsuran}</td>
                                        <td>${item.twm}</td>
                                        <td>${item.saldo_twm}</td>
                                        <td>${item.sisa_twm}</td>
                                    </tr>
                                `);
                            });

                            // SweetAlert jika data ditemukan
                            Swal.fire({
                                title: 'Data Ditemukan!',
                                text: response.data.length + ' data berhasil dimuat.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });

                        } else {
                            Swal.fire({
                                title: 'Data Tidak Ditemukan!',
                                text: 'Tidak ada data yang cocok dengan pencarian Anda.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            tbody.append('<tr><td colspan="12" class="text-center text-danger">Data tidak ditemukan</td></tr>');
                        }
                    },
                    error: function (xhr) {
                        Swal.close(); // Tutup loading
                            Swal.fire({
                                title: 'Terjadi Kesalahan!',
                                text: 'Gagal mengambil data, silakan coba lagi.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        console.error(xhr.responseText);
                    }
                });
            });

            // Fungsi ketika checkbox diubah
            $(document).on('change', '.realisasi-checkbox', function() {
                const baris = $(this).closest('tr');
                
                // Hanya menandai checkbox yang dipilih
                if ($(this).is(':checked')) {
                    // Simpan data yang diperlukan untuk jurnal
                    $(this).data({
                        'cif': $(this).data('cif'),
                    });
                } else {
                    // Hapus data jika tidak dicentang
                    $(this).removeData(['cif', 'plafond', 'margin', 'setoran']);
                }
            });

            // Fungsi simpan realisasi
            $('#realisasi').click(function() {
                const dataDipilih = [];
                
                $('.realisasi-checkbox:checked').each(function() {
                    dataDipilih.push({
                        cif: $(this).data('cif'),
                    });
                });

                if (dataDipilih.length === 0) {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Silakan pilih minimal 1 anggota untuk direalisasi!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Realisasi',
                    text: `Anda akan melakukan realisasi untuk ${dataDipilih.length} anggota. Lanjutkan?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Proses',
                    cancelButtonText: 'Batal'
                }).then((hasil) => {
                    if (hasil.isConfirmed) {
                        prosesRealisasi(dataDipilih);
                    }
                });
            });

            // Fungsi proses realisasi ke server
            function prosesRealisasi(data) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang menyimpan data',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('realisasi.tagihanKelompok.process') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        items: data
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Sukses!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Muat ulang data
                            $('#filterButton').click();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Gagal!',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    </script>
    
@endpush
