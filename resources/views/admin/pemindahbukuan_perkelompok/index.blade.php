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
        .anggota-check {
        transform: scale(1.2);
        }
        #proses-terpilih {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        .text-right {
            text-align: right;
        }
        .bg-light {
            background-color: #f8f9fa !important;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="code_kel" class="col-sm-2 col-form-label">Cari Kelompok</label>
                        <div class="col-sm-6">
                            <select class="form-control select2-ajax" id="code_kel" style="width: 100%;">
                                <!-- Opsi akan di-load via AJAX -->
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama Kelompok</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="nama_kelompok" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="jenis_pemindahan" class="col-sm-2 col-form-label">Jenis Pemindahbukuan</label>
                        <div class="col-sm-6">
                            <select name="jenis_pemindahan" class="form-control" id="jenis_pemindahan">
                                <option value="debet">Debet</option>
                                <option value="kredit">Kredit</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="jenis_simpanan" class="col-sm-2 col-form-label">Jenis Simpanan</label>
                        <div class="col-sm-6">
                            <select name="jenis_simpanan" class="form-control" id="jenis_simpanan">
                                <option value="pokok">Pokok</option>
                                <option value="wajib">Wajib</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-6 offset-sm-2">
                            <button id="filterButton" class="btn btn-primary">
                                <i class="fas fa-search"></i> Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Hasil Pencarian -->
            <div class="card">
                <div class="card-body">
                    <div id="result" class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="50px">Pilih</th>
                                    <th>CIF</th>
                                    <th>Nama</th>
                                    <th>Kelompok</th>
                                    <th>Jenis PB</th>
                                    <th>Jenis Simpanan</th>
                                    <th>Wajib</th>
                                    <th>Pokok</th>
                                    <th>Nominal PB</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan dimuat di sini -->
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
            // Inisialisasi Select2 dengan AJAX
            $('.select2-ajax').select2({
                placeholder: 'Cari Kode Kelompok...',
                // minimumInputLength: 3, // Minimal karakter untuk mulai pencarian
                ajax: {
                    url: "{{ route('pemindahbukuanPerkelompok.cariKelompok') }}", // Route untuk cari kelompok
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

            $('.select2-ajax').on('select2:select', function (e) {
                var data = e.params.data;
                var namaKelompok = data.text.split(' - ')[1]; // Ambil nama setelah strip
                $('#nama_kelompok').val(namaKelompok); // Ganti dengan ID yang tepat
            });
        });

        $(document).ready(function () {
            $('#filterButton').click(function () {
                var code_kel = $('#code_kel').val();
                var jenisPemindahan = $('#jenis_pemindahan').val();
                var jenisSimpanan = $('#jenis_simpanan').val();

                
                if (code_kel === '') {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Kode Kelompok harus diisi!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                
                $.ajax({
                    url: "{{ route('pemindahbukuanPerkelompok.filter') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        code_kel: code_kel,
                        jenis_pemindahan: jenisPemindahan,
                        jenis_simpanan: jenisSimpanan,
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
                            // Inisialisasi total
                            var totalWajib = 0;
                            var totalPokok = 0;
                            
                            // Data anggota dengan checkbox
                            $.each(response.anggota, function(index, anggota) {
                                totalWajib += parseFloat(anggota.wajib) || 0;
                                totalPokok += parseFloat(anggota.pokok) || 0;
                                
                                tbody.append(`
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>
                                            <input type="checkbox" class="anggota-check" 
                                                data-id="${anggota.no}" checked>
                                        </td>
                                        <td>${anggota.cif}</td>
                                        <td>${anggota.nama}</td>
                                        <td>${anggota.kode_kel}</td>
                                        <td>${jenisPemindahan}</td>
                                        <td>${jenisSimpanan}</td>
                                        <td class="text-right">${formatRupiah(anggota.pokok)}</td>
                                        <td class="text-right">${formatRupiah(anggota.wajib)}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm setoran-input" 
                                                value="0"
                                                data-id="${anggota.no}"
                                                style="width: 100px;">
                                        </td>
                                    </tr>
                                `);
                            });
                            // Tambahkan baris total
                            tbody.append(`
                                <tr style="font-weight: bold; background-color: #f5f5f5;">
                                    <td colspan="7" class="text-center">TOTAL</td>
                                    <td class="text-right">${formatRupiah(totalWajib)}</td>
                                    <td class="text-right">${formatRupiah(totalPokok)}</td>
                                    <td></td>
                                </tr>
                            `);
                            // Tambahkan tombol aksi di bawah tabel
                            $('.table-responsive').after(`
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="select-all" checked>
                                            <label class="form-check-label" for="select-all">
                                                Semua
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button id="proses-terpilih" class="btn btn-primary">
                                            <i class="fas fa-check-circle"></i> Proses
                                        </button>
                                    </div>
                                </div>
                            `);
                            // Select all functionality
                            $('#select-all').change(function() {
                                $('.anggota-check').prop('checked', $(this).prop('checked'));
                            });
                            // Proses yang dipilih
                            $('#proses-terpilih').click(function() {
                                prosesAnggotaTerpilih(code_kel);
                            });
                            
                        } else {
                            tbody.append('<tr><td colspan="9" class="text-center">Tidak ada data anggota</td></tr>');
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

            function prosesAnggotaTerpilih(code_kel) {
                const pilihAnggota = [];
                const inputNyataSetor = {};
                const jenisPemindahan = $('#jenis_pemindahan').val();
                const jenisSimpanan = $('#jenis_simpanan').val();
                
                $('.anggota-check:checked').each(function() {
                    const id = $(this).data('id');
                    const setoran = $(`.setoran-input[data-id="${id}"]`).val();

                    pilihAnggota.push(id);
                    inputNyataSetor[id] = parseFloat(setoran) || 0;

                });
                
                $.ajax({
                    url: `/transaksi/pemindahbukuan-perkelompok/proses/${code_kel}`,
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        pilih_anggota: pilihAnggota,
                        input_nyata_setor: inputNyataSetor,
                        jenis_pemindahan: jenisPemindahan,
                        jenis_simpanan: jenisSimpanan,
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload(); // Reload halaman setelah sukses
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Gagal memproses data',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
        
    </script>
@endpush