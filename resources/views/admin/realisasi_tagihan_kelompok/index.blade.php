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
                                    <th>
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    <th>NO</th>
                                    <th>CIF</th>
                                    <th>NAMA</th>
                                    <th>UNIT</th>
                                    <th>PEMBIAYAAN</th>
                                    <th>MARGIN</th>
                                    <th>SETORAN</th>
                                    <th>ANGSURAN</th>
                                    <th>TWM</th>
                                    <th>SALDO TWM</th>
                                    <th>SISA TWM</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot id="total-row" style="display: none;">
                                <tr>
                                    <th colspan="5" class="text-right">TOTAL</th>
                                    <th class="text-right" id="total-pembiayaan">0</th>
                                    <th class="text-right" id="total-margin">0</th>
                                    <th class="text-right" id="total-setoran">0</th>
                                    <th class="text-right" id="total-angsuran">0</th>
                                    <th class="text-right" id="total-twm">0</th>
                                    <th class="text-right" id="total-saldo-twm">0</th>
                                    <th class="text-right" id="total-sisa-twm">0</th>
                                </tr>
                            </tfoot>
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
            // Fungsi untuk memformat angka dengan separator ribuan
            function formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }

            // Fungsi untuk memeriksa validasi data
            function cekValidasiRealisasi(item) {
                const twm = parseFloat(item.twm) || 0;
                const angsuran = parseFloat(item.angsuran) || 0;
                return twm > angsuran;
            }

            // Event klik tombol filter
            $('#filterButton').click(function() {
                const code_kel = $('#code_kel').val().trim();

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
                    text: 'Sedang mengambil data',
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
                    success: function(response) {
                        Swal.close();
                        const tbody = $('table tbody');
                        tbody.empty();
                        $('#checkAll').prop('checked', false);

                        // Inisialisasi total
                        let totalPembiayaan = 0;
                        let totalMargin = 0;
                        let totalSetoran = 0;
                        let totalAngsuran = 0;
                        let totalTwm = 0;
                        let totalSaldoTwm = 0;
                        let totalSisaTwm = 0;

                        if (response.data.length > 0) {
                            // Loop hasil pencarian
                            $.each(response.data, function(index, item) {
                                // Konversi ke number
                                const pembiayaan = parseFloat(item.os) || 0;
                                const margin = parseFloat(item.saldo_margin) || 0;
                                const setoran = parseFloat(item.bulat) || 0;
                                const angsuran = parseFloat(item.angsuran) || 0;
                                const twm = parseFloat(item.twm) || 0;
                                const saldoTwm = parseFloat(item.saldo_twm) || 0;
                                const sisaTwm = parseFloat(item.sisa_twm) || 0;

                                // Hitung total
                                totalPembiayaan += pembiayaan;
                                totalMargin += margin;
                                totalSetoran += setoran;
                                totalAngsuran += angsuran;
                                totalTwm += twm;
                                totalSaldoTwm += saldoTwm;
                                totalSisaTwm += sisaTwm;

                                // Cek validasi
                                const isValid = cekValidasiRealisasi(item);
                                const rowClass = isValid ? '' : 'text-danger';

                                // Tambahkan row ke tabel
                                tbody.append(`
                                    <tr class="${rowClass}">
                                        <td class="text-center">
                                            <input type="checkbox" class="realisasi-checkbox" 
                                                data-cif="${item.cif}"
                                                data-valid="${isValid}">
                                        </td>
                                        <td>${index + 1}</td>
                                        <td>${item.cif}</td>
                                        <td>${item.nama}</td>
                                        <td>${item.unit}</td>
                                        <td class="text-right">${formatNumber(pembiayaan)}</td>
                                        <td class="text-right">${formatNumber(margin)}</td>
                                        <td class="text-right">${formatNumber(setoran)}</td>
                                        <td class="text-right">${formatNumber(angsuran)}</td>
                                        <td class="text-right">${formatNumber(twm)}</td>
                                        <td class="text-right">${formatNumber(saldoTwm)}</td>
                                        <td class="text-right">${formatNumber(sisaTwm)}</td>
                                    </tr>
                                `);
                            });

                            // Update total footer
                            $('#total-pembiayaan').text(formatNumber(totalPembiayaan));
                            $('#total-margin').text(formatNumber(totalMargin));
                            $('#total-setoran').text(formatNumber(totalSetoran));
                            $('#total-angsuran').text(formatNumber(totalAngsuran));
                            $('#total-twm').text(formatNumber(totalTwm));
                            $('#total-saldo-twm').text(formatNumber(totalSaldoTwm));
                            $('#total-sisa-twm').text(formatNumber(totalSisaTwm));

                            // Tampilkan footer total
                            $('#total-row').show();

                            // Notifikasi sukses
                            Swal.fire({
                                title: 'Data Ditemukan!',
                                text: `Ditemukan ${response.data.length} data anggota`,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });

                        } else {
                            // Tampilkan pesan jika data tidak ditemukan
                            Swal.fire({
                                title: 'Data Tidak Ditemukan!',
                                text: 'Tidak ada data yang cocok dengan pencarian Anda.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            tbody.append(
                                '<tr><td colspan="12" class="text-center text-danger">Data tidak ditemukan</td></tr>'
                            );
                            // Sembunyikan footer total
                            $('#total-row').hide();
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal mengambil data. Silakan coba lagi.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        console.error(xhr.responseText);
                    }
                });
            });

            // Fungsi check all
            $('#checkAll').click(function() {
                $('.realisasi-checkbox').prop('checked', $(this).prop('checked'));
            });

            // Event ketika checkbox diubah
            $(document).on('change', '.realisasi-checkbox', function() {
                const allChecked = $('.realisasi-checkbox:checked').length === 
                                 $('.realisasi-checkbox').length;
                $('#checkAll').prop('checked', allChecked);
            });

            // Event klik tombol realisasi
            $('#realisasi').click(function() {
                const dataDipilih = [];
                const dataTidakValid = [];

                $('.realisasi-checkbox:checked').each(function() {
                    const cif = $(this).data('cif');
                    const isValid = $(this).data('valid');
                    
                    if (isValid) {
                        dataDipilih.push({ cif: cif });
                    } else {
                        const row = $(this).closest('tr');
                        const nama = row.find('td:eq(2)').text();
                        dataTidakValid.push(nama);
                    }
                });

                if (dataDipilih.length === 0 && dataTidakValid.length === 0) {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Silakan pilih minimal 1 anggota untuk direalisasi!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                if (dataTidakValid.length > 0) {
                    Swal.fire({
                        title: 'Gagal Melakukan Realisasi!',
                        html: `Anggota dgn CIF <b>${dataTidakValid.map(nama => `${nama}`).join('')}</b> tidak memenuhi syarat realisasi.</p>
                        <p>Apakah Anda ingin tetap memproses data yang valid saja?</p>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Proses Data Valid Saja',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed && dataDipilih.length > 0) {
                            prosesRealisasi(dataDipilih);
                        }
                    });
                } else {
                    // Konfirmasi sebelum realisasi
                    Swal.fire({
                        title: 'Konfirmasi Realisasi',
                        html: `Anda akan merealisasi <b>${dataDipilih.length} anggota</b>. Lanjutkan?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Proses',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            prosesRealisasi(dataDipilih);
                        }
                    });
                }
            });

            // Fungsi proses realisasi ke backend
            function prosesRealisasi(data) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang menyimpan data realisasi',
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
                            // Refresh data setelah berhasil
                            $('#filterButton').click();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat memproses realisasi';
                        
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                errorMessage = xhr.responseJSON.errors.join('<br>');
                            }
                        }
                        
                        Swal.fire({
                            title: 'Gagal!',
                            html: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    </script>
@endpush