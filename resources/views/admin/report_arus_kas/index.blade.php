@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Laporan Arus Kas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Laporan Arus Kas</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <form id="formArusKas" action="{{ route('report.arus-kas.generate') }}" method="POST">
                @csrf

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tanggal Awal</label>
                    <div class="col-sm-4">
                        <input type="date" name="tanggal_awal" class="form-control" value="{{ $periodeAwal }}" required>
                    </div>
                    <label class="col-sm-1 col-form-label text-center">s/d</label>
                    <div class="col-sm-4">
                        <input type="date" name="tanggal_akhir" class="form-control" value="{{ $periodeAkhir }}" required>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>
    </div>

    @if($rekonsiliasi)
        <div class="alert {{ abs($rekonsiliasi['selisih']) < 1 ? 'alert-success' : 'alert-warning' }}">
            <strong>Rekonsiliasi Kas:</strong>
            Total netto arus kas Rp {{ number_format($rekonsiliasi['total_netto_kategori'], 0, ',', '.') }},
            perubahan saldo riil akun Kas &amp; Bank Rp {{ number_format($rekonsiliasi['perubahan_kas_riil'], 0, ',', '.') }},
            selisih Rp {{ number_format($rekonsiliasi['selisih'], 0, ',', '.') }}.
            @if(abs($rekonsiliasi['selisih']) >= 1)
                Ada selisih - periksa kembali mapping COA di <code>coa_arus_kas_mappings</code>.
            @endif
            @if($rekonsiliasi['unclassified_debet'] > 0 || $rekonsiliasi['unclassified_kredit'] > 0)
                <br><strong>Belum Terklasifikasi:</strong>
                debet Rp {{ number_format($rekonsiliasi['unclassified_debet'], 0, ',', '.') }},
                kredit Rp {{ number_format($rekonsiliasi['unclassified_kredit'], 0, ',', '.') }}
                - ada kode rekening yang belum ada di tabel mapping.
            @endif
        </div>
    @endif

    @if($rows->isNotEmpty())
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Uraian</th>
                            <th class="text-end">Mutasi Debet</th>
                            <th class="text-end">Mutasi Kredit</th>
                            <th class="text-end">Saldo Awal</th>
                            <th class="text-end">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kategori as $code => $meta)
                            @php $row = $rows->get($code); @endphp
                            <tr class="{{ $meta['line'] === 'HEADING' ? 'table-secondary' : '' }}">
                                <td class="{{ $meta['line'] === 'DETAIL' ? 'ps-4' : '' }}">
                                    <strong>{{ $meta['nama'] }}</strong>
                                </td>
                                @if($meta['line'] === 'DETAIL')
                                    <td class="text-end">{{ number_format($row->mut_debet ?? 0, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($row->mut_kredit ?? 0, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($row->saldo_awal ?? 0, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($row->saldo_akhir ?? 0, 0, ',', '.') }}</td>
                                @else
                                    <td colspan="4"></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.getElementById('formArusKas').addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Memproses Data...',
                text: 'Mohon tunggu, generate arus kas bisa memakan waktu beberapa saat',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            setTimeout(() => {
                this.submit();
            }, 500);
        });
    </script>
@endpush
