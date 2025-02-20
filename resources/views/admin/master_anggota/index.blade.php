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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('anggota.create') }}" class="btn btn-sm btn-primary">+ Tambah</a>
                    {{-- <button onclick="addForm('{{ route('anggota.store') }}')" class="btn btn-sm btn-primary">+ Tambah</button> --}}
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Anggota</th>
                                <th>CIF</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>TTL</th>
                                <th>AP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

{{-- @include('admin.master_anggota.form') --}}
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <script>
        
        function searchByNik() {
            const nik = document.getElementById('nikInput').value;
            const resultContainer = document.getElementById('resultContainer');

            // Kosongkan hasil sebelumnya
            resultContainer.innerHTML = '';

            if (!nik) {
                alert('Silakan masukkan NIK!');
                return;
            }

            // Lakukan request ke backend
            // fetch(`http://185.201.9.210/apimobcol/rmc.php?ktp=${nik}`)
            fetch(`http://localhost/data-anggota-api/public/api/files/${nik}`)
            // fetch(`/proxy/search?ktp=${ktp}`)
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
                                    <img src="http://localhost/data-anggota-api/public/storage/uploads/${data.generated_name}" width="450px" alt="Gambar KTP">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-11">
                            <div class="card mb-3">
                                <div class="card-header">KTP</div>
                                <div class="card-body">
                                    <img src="http://localhost/data-anggota-api/public/storage/uploads/${data.generated_name}" width="450px" alt="Gambar KTP">
                                </div>
                            </div>
                        </div>
                    `;

                    // <div class="col-sm-11">
                    //     <div class="card mb-3">
                    //         <div class="card-header">KTP</div>
                    //         <div class="card-body">
                    //             <img src="http://rmc.nurinsani.co.id:9373/berkas/${data.data[0].kk}" width="450px" alt="Gambar KTP">
                    //         </div>
                    //     </div>
                    // </div>

                })
                .catch(error => {
                    // Tampilkan pesan error
                    resultContainer.innerHTML = `<p style="color: red;">${error.message}</p>`;
                });
        }

        let table;

        $(function() {
            table = $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('anggota.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'no'
                    },
                    {
                        data: 'cif'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'alamat'
                    },
                    {
                        data: null, // Gabungkan field tempat_lahir dan tgl_lahir
                        render: function(data, type, row) {
                            return `${row.tempat_lahir}, ${row.tgl_lahir}`;
                        }
                    },
                    {
                        data: 'unit'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ]
            })
        });

        function hapusData(url) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        Swal.fire(
                            'Terhapus!',
                            'Data berhasil dihapus.',
                            'success'
                        );
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        Swal.fire(
                            'Gagal!',
                            'Data tidak dapat dihapus.',
                            'error'
                        );
                    });
                }
            });
        }


    </script>
@endpush
