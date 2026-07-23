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
                    <!-- Grup Blokir -->
                    <div class="form-group row">
                        <label for="grup_blokir" class="col-sm-2 col-form-label">Grup Blokir</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="grup_blokir" name="grup_blokir">
                                <option value="" selected>-- pilih Grup Blokir --</option>
                                <option value="kelompok">Kelompok</option>
                                <option value="individu">Perorangan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row" id="wrapper_kelompok" style="display: none;">
                        <label for="kelompok" class="col-sm-2 col-form-label">Kode Kelompok</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="kelompok" name="kelompok">
                                <option value="">-- pilih Kode Kelompok --</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row" id="wrapper_cif" style="display: none;">
                        <label for="cif" class="col-sm-2 col-form-label">CIF</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="cif" name="cif">
                                <option value="">-- pilih CIF --</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="jenis_blokir" class="col-sm-2 col-form-label">Jenis Blokir</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="jenis_blokir" name="jenis_blokir">
                                <option value="">-- pilih Jenis Blokir --</option>
                                <option value="tutup">Tutup</option>
                                <option value="buka">Buka</option>
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

            <div class="row mt-3" style="display: block;">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered text-center mb-0">
                                <thead style="background: #f5f5f5;">
                                    <tr>
                                        <th>NOREK</th>
                                        <th>NAMA</th>
                                        <th>TGL TRANSAKSI</th>
                                        <th>JENIS BLOKIR</th>
                                        <th>DEBET</th>
                                        <th>KREDIT</th>
                                        <th>SALDO</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@push('scripts')
<script>
    $(document).ready(function() {
        $('#kelompok').select2({
            width: '100%',
            placeholder: "-- Ketik kode / nama kelompok --",
            minimumInputLength: 1,
            theme: 'bootstrap4',
            ajax: {
                url: "{{ route('al.get.kelompok') }}",
                dataType: 'json',
                delay: 250, 
                data: function (params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#cif').select2({
            width: '100%',
            placeholder: "-- Ketik CIF --",
            minimumInputLength: 1,
            theme: 'bootstrap4',
            ajax: {
                url: "{{ route('al.get.cif') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        function updateGrupBlokir() {
            let nilai = $('#grup_blokir').val();

            // Sembunyikan wrapper
            $('#wrapper_kelompok').hide();
            $('#wrapper_cif').hide();
            
            // Reset nilai dan beritahu Select2 agar tampilannya ikut ter-reset
            $('#kelompok').val('').trigger('change');
            $('#cif').val('').trigger('change');

            if (nilai === 'kelompok') {
                $('#wrapper_kelompok').show();
                loadKelompokData();
            } else if (nilai === 'individu') {
                $('#wrapper_cif').show();
                loadCifData(); 
            }
        }

        function loadKelompokData() {
            $.ajax({
                url: "{{ route('al.get.kelompok') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#kelompok').html('<option value="">-- pilih Kode Kelompok --</option>');
                    $.each(data, function(key, value) {
                        $('#kelompok').append('<option value="'+ value.code_kel +'">'+ value.code_kel +' - '+ value.nama_kel +'</option>');
                    });
                    
                    $('#kelompok').trigger('change');
                },
                error: function(xhr) {
                    console.log("Gagal mengambil data kelompok");
                }
            });
        }

        function loadCifData() {
            $.ajax({
                url: "{{ route('al.get.cif') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#cif').html('<option value="">-- pilih CIF --</option>');
                    $.each(data, function(key, value) {
                        $('#cif').append('<option value="'+ value.cif +'">'+ value.cif +' - '+ value.nama +'</option>');
                    });
                    
                    $('#cif').trigger('change');
                },
                error: function(xhr) {
                    console.log("Gagal mengambil data CIF");
                }
            });
        }

        $('#grup_blokir').on('change', function() {
            updateGrupBlokir();
        });

        $('#filterButton').on('click', function(e) {
            e.preventDefault();

            let grupBlokir  = $('#grup_blokir').val();
            let kelompok    = $('#kelompok').val();
            let cif         = $('#cif').val();
            let jenisBlokir = $('#jenis_blokir').val();

            // Validasi sederhana sebelum kirim (opsional)
            if (!grupBlokir || !jenisBlokir) {
            Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Grup Blokir dan Jenis Blokir wajib diisi!'
                });
                return;
            }

            $.ajax({
                url: "{{ route('update.blokir.simpanan') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    grup_blokir: grupBlokir,
                    kelompok: kelompok,
                    cif: cif,
                    jenis_blokir: jenisBlokir
                },
                success: function(response) {
                    let tbody = '';
                    let saldoBerjalan = 0; 

                    if(response.data.length > 0) {
                        $.each(response.data, function(index, row) {
                            let debet = parseFloat(row.debet) || 0;
                            let kredit = parseFloat(row.kredit) || 0;

                            saldoBerjalan = saldoBerjalan + kredit - debet;

                            tbody += `<tr>
                                <td>${row.norek}</td>
                                <td>${row.nama ?? '-'}</td>
                                <td>${row.tgl_input ?? '-'}</td>
                                <td>${row.blok}</td>
                                <td>${debet.toLocaleString('id-ID')}</td>
                                <td>${kredit.toLocaleString('id-ID')}</td>
                                <td>${saldoBerjalan.toLocaleString('id-ID')}</td>
                            </tr>`;
                        });
                    } else {
                        tbody = `<tr><td colspan="7" class="text-center">Tidak ada data ditemukan</td></tr>`;
                    }
                    
                    $('tbody').html(tbody);

                    // MUNCULKAN SWEETALERT SAAT BERHASIL
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Status blokir simpanan berhasil diperbarui!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan pada server.'
                    });
                }
            });
        });       
    });
</script>
@endpush