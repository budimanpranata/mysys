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
            <div class="card">
                <div class="card-header">
                    Data {{ $title }}
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="tabel-transaksi">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Unit</th>
                                <th>Tanggal Transaksi</th>
                                <th>Kode Transaksi</th>
                                <th>Kode Rekening</th>
                                <th>Keterangan</th>
                                <th>Debet</th>
                                <th>Kredit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#tabel-transaksi').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('transaksi.data') }}", // Route untuk mengambil data JSON
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'unit', name: 'unit' },
                { data: 'tanggal_transaksi', name: 'tanggal_transaksi' },
                { data: 'kode_transaksi', name: 'kode_transaksi' },
                { data: 'kode_rekening', name: 'kode_rekening' },
                { data: 'keterangan_transaksi', name: 'keterangan_transaksi' },
                { data: 'debet', name: 'debet' },
                { data: 'kredit', name: 'kredit' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
            ]
        });
    });

    // Fungsi SweetAlert untuk Konfirmasi Hapus
    function deleteData(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data transaksi yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }

    // SweetAlert untuk Notifikasi Sukses dari Session Laravel
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @endif
</script>
@endpush