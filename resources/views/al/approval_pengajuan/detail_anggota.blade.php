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
    <div class="card mt-3">
        @include('al/approval_pengajuan/form_detail_anggota')
    </div>

    <!-- Modal untuk review gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Review Gambar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Gambar" class="img-fluid"
                        style="cursor: zoom-in; transition: transform 0.3s ease;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="rotateImage()">Rotate</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>

        const rupiah = new Intl.NumberFormat('id-ID');

        $('#btnApprove').on('click', function (e) {
            e.preventDefault();

            if (!confirm('Yakin approve pengajuan ini?')) {
                return;
            }

            $.ajax({
                url: "{{ url('/al/approval-pengajuan/approve/' . $detail->no_anggota) }}",
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    alert(res.message);

                    if (res.redirect) {
                        window.location.href = res.redirect;
                    }
                },
                error: function (xhr) {
                    alert('Gagal approve');
                    console.log(xhr.responseText);
                }
            });
        });

        let zoomed = false;
        let rotation = 0;

        // fungsi zoom on click
        document.addEventListener("click", function(e) {
            const img = document.getElementById("modalImage");
            if (e.target === img) {
                zoomed = !zoomed;
                applyTransform(img);
                img.style.cursor = zoomed ? "zoom-out" : "zoom-in";
            }
        });

        // fungsi reset + tampilkan gambar
        function openImageModal(imageUrl) {
            const img = document.getElementById('modalImage');
            img.src = imageUrl;
            zoomed = false;
            rotation = 0;
            applyTransform(img);
            img.style.cursor = "zoom-in";
        }

        // fungsi rotate
        function rotateImage() {
            const img = document.getElementById("modalImage");
            rotation = (rotation + 90) % 360; // tiap klik +90 derajat
            applyTransform(img);
        }

        // helper untuk gabungkan scale + rotate
        function applyTransform(img) {
            const scale = zoomed ? 2 : 1;
            img.style.transform = `scale(${scale}) rotate(${rotation}deg)`;
        }

        function openImageModal(imageUrl) {
            // Set sumber gambar di modal
            document.getElementById('modalImage').src = imageUrl;
        }

        async function cariKtp() {
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

            try {
                // Kirim request ke controller Laravel
                const response = await fetch('/al/approval-pengajuan/cari-ktp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ nik })
                });

                if (!response.ok) {
                    throw new Error('Data tidak ditemukan');
                }

                const data = await response.json();

                // Tampilkan hasil pencarian
                resultContainer.innerHTML = `
                <div class="row">
                    <div class="col-sm-3">
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

                    <div class="col-sm-3">
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

                    <div class="col-sm-3">
                        <div class="card mb-3">
                            <div class="card-header">Data Penjamin</div>
                            <div class="card-body">
                                <img src="http://rmc.nurinsani.co.id:9373/berkas/${data.data[0].penjamin}" 
                                    width="580px" 
                                    alt="Gambar penjamin" 
                                    class="img-thumbnail" 
                                    style="cursor: pointer;" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#imageModal"
                                    onclick="openImageModal('http://rmc.nurinsani.co.id:9373/berkas/${data.data[0].penjamin}')">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="card mb-3">
                            <div class="card-header">Foto Usaha</div>
                            <div class="card-body">
                                <img src="http://rmc.nurinsani.co.id:9373/berkas/${data.data[0].usaha}" 
                                    width="580px" 
                                    alt="Gambar usaha" 
                                    class="img-thumbnail" 
                                    style="cursor: pointer;" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#imageModal"
                                    onclick="openImageModal('http://rmc.nurinsani.co.id:9373/berkas/${data.data[0].usaha}')">
                            </div>
                        </div>
                    </div>
                </div>
                `;
            } catch (error) {
                // Tampilkan pesan error
                resultContainer.innerHTML = `
                    <div class="col-sm-12">
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle fa-lg mr-2"></i>
                            <strong>Berkas tidak ditemukan</strong>
                            <div class="mt-1">Silakan periksa kembali input atau kriteria pencarian</div>
                        </div>
                    </div>
                `;
            }
        }
    </script>
@endsection
