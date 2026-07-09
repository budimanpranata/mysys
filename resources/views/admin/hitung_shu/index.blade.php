@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Hitung SHU</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Hitung SHU</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body text-center">
            <p class="text-muted">
                Proses ini adalah proses untuk menghasilkan laporan keuangan yaitu menghitung Rugi Laba.
            </p>

            @if($adaBelumPosting)
                <p class="text-muted">
                    Proses bisa dilakukan setelah semua data diposting - masih ada transaksi yang belum diposting.
                </p>
            @else
                <form id="formHitungShu" action="{{ route('hitung-shu.proses') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Proses</button>
                </form>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('formHitungShu')?.addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: 'Proses ini akan menghitung ulang saldo Neraca dan Rugi Laba.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Proses',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: @json(session('success'))
            });
        @endif
    </script>
@endpush
