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
                        <span class="col-sm-2 col-form-label">Pilih Tanggal Akad</span>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" id="tgl_murab">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6 offset-sm-2">
                            <button id="filterButton" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div id="result">
                        <!-- Tabel untuk menampilkan data -->
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>TANGGAL MURABAHAH</th>
                                    <th>CIF</th>
                                    <th>NIK</th>
                                    <th>NAMA ANGGOTA</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <!-- Tambahkan tombol cetak di bawah tabel -->
                    <div class="mt-3 text-right">
                        <button id="cetakButton" class="btn btn-danger"><i class="fas fa-print"></i> Cetak PDF</button>
                    </div>
                </div>
            </div>            
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('filterButton').addEventListener('click', function(e) {
            e.preventDefault();

            const tglMurab = document.getElementById('tgl_murab').value;

            // Validasi jika tanggal tidak dipilih
            if (!tglMurab) {
                Swal.fire({
                    title: 'Harap pilih tanggal akad!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            fetch('{{ route('cetak-murabahah.filter') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        tgl_murab: tglMurab
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector('#result tbody');
                    tbody.innerHTML = '';

                    if (data.status === 'success' && data.data.length > 0) {
                        data.data.forEach((item, index) => {
                            // Format tanggal
                            const date = new Date(item.tgl_murab);
                            const formattedDate = date.toLocaleDateString('id-ID', {
                                weekday: 'long', // Hari
                                day: 'numeric', // Tanggal
                                month: 'long', // Bulan
                                year: 'numeric' // Tahun
                            });

                            const row = `<tr>
                                <td>${index + 1}</td>
                                <td>${formattedDate}</td>
                                <td>${item.cif}</td>
                                <td>${item.no_anggota}</td>
                                <td>${item.nama}</td>
                            </tr>`;
                            tbody.innerHTML += row;
                        });
                    } else {
                        // Alert jika data tidak ditemukan
                        // alert('Data tidak ditemukan untuk tanggal yang dipilih.');
                        Swal.fire({
                            title: 'Data tidak ditemukan untuk tanggal yang dipilih!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                            title: 'Terjadi kesalahan saat mengambil data!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        return;
                    // alert('Terjadi kesalahan saat mengambil data.');
                });
        });



        document.getElementById('cetakButton').addEventListener('click', function(e) {
            e.preventDefault();

            const tglMurab = document.getElementById('tgl_murab').value;

            // if (!tglMurab) {
            //     alert('Harap pilih tanggal akad!');
            // }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('cetak-murabahah.pdf') }}';
            // form.target = '_blank'; // Membuka form di tab baru

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';

            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'tgl_murab';
            dateInput.value = tglMurab;

            form.appendChild(csrfInput);
            form.appendChild(dateInput);

            document.body.appendChild(form);
            form.submit();
        });
    </script>
@endpush
