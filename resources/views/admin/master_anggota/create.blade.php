@extends('layouts.main')

@section('content-header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ $title }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Dahsboard</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @include('admin.master_anggota.form')

    <!-- Modal untuk review gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Review Gambar</h5>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Gambar" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function openImageModal(imageUrl) {
            // Set sumber gambar di modal
            document.getElementById('modalImage').src = imageUrl;
        }

        $(document).ready(function() {
            // Ketika dropdown kelompok dipilih
            $('#code_kel').change(function() {
                // Ambil nilai yang dipilih
                var selectedCode = $(this).val();
                console.log("Kode Kelompok yang dikirim:", selectedCode); // Debug

                // Lakukan AJAX request untuk mendapatkan data kelompok
                if (selectedCode) {
                    $.ajax({
                        url: '/get-kelompok-data', // Ganti dengan URL endpoint Anda
                        type: 'GET',
                        data: { code_kel: selectedCode },
                        success: function(response) {
                            console.log("Response dari Server:", response); // Debug
                            // Isi data ke input fields
                            $('#nama_ao').val(response.nama_ao);
                            $('#no_tlp').val(response.no_tlp);
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    // Kosongkan input fields jika tidak ada kelompok yang dipilih
                    $('#cao').val('');
                    $('#no_tlp').val('');
                }
            });
        });

        function searchByNik() {
            const nik = document.getElementById('nikInput').value;
            const resultContainer = document.getElementById('resultContainer');

            // Kosongkan hasil sebelumnya
            resultContainer.innerHTML = '';

            if (!nik) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'NIK harus diisi!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Lakukan request ke backend
            fetch(`http://mobcol.nurinsani.co.id/apimobcol/rmcKtp.php?ktp=${nik}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Data tidak ditemukan');
                    }
                    return response.json();
                })
                .then(data => {
                    // Tampilkan hasil pencarian
                    resultContainer.innerHTML = `

                    <div class="col-sm-11">
                        <div class="card mb-3">
                            <div class="card-header">KTP</div>
                            <div class="card-body">
                                <img src="http://rmc.nurinsani.co.id:9373/berkas/${data.data[0].ktp}" 
                                    width="580px" 
                                    alt="Gambar KTP" 
                                    class="img-thumbnail" 
                                    style="cursor: pointer;" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#imageModal"
                                    onclick="openImageModal('http://rmc.nurinsani.co.id:9373/berkas/${data.data[0].ktp}')">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-11">
                        <div class="card mb-3">
                            <div class="card-header">Kartu Keluarga</div>
                            <div class="card-body">
                                <img src="http://rmc.nurinsani.co.id:9373/berkas/${data.data[0].kk}" 
                                    width="580px" 
                                    alt="Gambar KK" 
                                    class="img-thumbnail" 
                                    style="cursor: pointer;" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#imageModal"
                                    onclick="openImageModal('http://rmc.nurinsani.co.id:9373/berkas/${data.data[0].kk}')">
                            </div>
                        </div>
                    </div>
                `;


                })
                .catch(error => {
                    // Tampilkan pesan error
                    resultContainer.innerHTML = `<p style="color: red;">${error.message}</p>`;
                });
        }

        $(document).ready(function() {
            // Handle form submission
            $('#form-tambah-anggota').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit default

                $.ajax({
                    url: '/anggota',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                        } else {
                            alert('Gagal: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        // Tangani error
                        var response = xhr.responseJSON;
                        if (response && response.message) {
                            alert('Error: ' + response.message);
                        } else {
                            alert('Terjadi kesalahan pada server.');
                        }
                    }
                });
            });
        });
    </script>
@endpush
