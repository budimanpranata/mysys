@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Posting Jurnal</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Posting Jurnal</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            Tanggal Sistem: <strong>{{ \Carbon\Carbon::parse($tglSystem)->format('d/m/Y') }}</strong>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Kode Rekening</th>
                        <th>Nama Rekening</th>
                        <th class="text-end">Debet</th>
                        <th class="text-end">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>{{ $row->kode_rekening }}</td>
                            <td>{{ $row->nama_rek }}</td>
                            <td class="text-end">{{ number_format($row->debet, 2, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row->kredit, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada transaksi yang belum di-posting</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($rows->isNotEmpty())
                    <tfoot>
                        <tr class="table-secondary">
                            <td colspan="2" class="text-center"><strong>TOTAL TRANSAKSI</strong></td>
                            <td class="text-end"><strong>{{ number_format($total->tot_debet, 2, ',', '.') }}</strong></td>
                            <td class="text-end"><strong>{{ number_format($total->tot_kredit, 2, ',', '.') }}</strong></td>
                        </tr>
                    </tfoot>
                @endif
            </table>

            @if($rows->isNotEmpty())
                <form id="formPosting" action="{{ route('posting-jurnal.posting') }}" method="POST" class="text-center">
                    @csrf
                    <button type="submit" class="btn btn-primary">POSTING JURNAL</button>
                </form>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('formPosting')?.addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: 'Transaksi hari ini akan diposting ke buku besar dan tidak bisa diedit lagi.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Posting',
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
                title: 'Berhasil',
                text: @json(session('success'))
            });
        @endif
    </script>
@endpush
