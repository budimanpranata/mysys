@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Neraca</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Neraca</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@php
    $fmtParens = function ($v) {
        $v = (float) $v;
        return $v < 0 ? '(' . number_format(abs($v), 0, ',', '.') . ')' : number_format($v, 0, ',', '.');
    };
    $fmtPlain = fn ($v) => number_format((float) $v, 0, ',', '.');
@endphp

@section('content')
    <div class="card mb-4">
        <div class="card-body text-end">
            <a href="{{ route('report.neraca.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th rowspan="2">Type COA</th>
                        <th rowspan="2">COA</th>
                        <th rowspan="2">Deskripsi</th>
                        <th rowspan="2" class="text-end">Saldo Awal</th>
                        <th colspan="2" class="text-center">Mutasi</th>
                        <th rowspan="2" class="text-end">Saldo Akhir</th>
                    </tr>
                    <tr>
                        <th class="text-end">Debet</th>
                        <th class="text-end">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aktiva as $row)
                        <tr class="{{ $row->LINE_BALANCE === 'HEADING' ? 'table-secondary fw-bold' : '' }}">
                            <td>{{ $row->LINE_BALANCE }}</td>
                            <td>{{ $row->kode_rekening }}</td>
                            <td>{{ $row->nama_rekening }}</td>
                            <td class="text-end">{{ $fmtParens($row->saldo_awal) }}</td>
                            <td class="text-end">{{ $fmtPlain($row->mut_debet) }}</td>
                            <td class="text-end">{{ $fmtPlain($row->mut_kredit) }}</td>
                            <td class="text-end">{{ $fmtParens($row->saldo_akhir) }}</td>
                        </tr>
                    @endforeach
                    <tr class="table-dark text-white">
                        <td colspan="3"><strong>TOTAL AKTIVA</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($totalAktiva->saldo_awal) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($totalAktiva->mut_debet) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($totalAktiva->mut_kredit) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($totalAktiva->saldo_akhir) }}</strong></td>
                    </tr>
                    <tr><td colspan="7"></td></tr>

                    @foreach($pasiva as $row)
                        <tr class="{{ $row->LINE_BALANCE === 'HEADING' ? 'table-secondary fw-bold' : '' }}">
                            <td>{{ $row->LINE_BALANCE }}</td>
                            <td>{{ $row->kode_rekening }}</td>
                            <td>{{ $row->nama_rekening }}</td>
                            <td class="text-end">{{ $fmtParens($row->saldo_awal) }}</td>
                            <td class="text-end">{{ $fmtPlain($row->mut_debet) }}</td>
                            <td class="text-end">{{ $fmtPlain($row->mut_kredit) }}</td>
                            <td class="text-end">{{ $fmtParens($row->saldo_akhir) }}</td>
                        </tr>
                    @endforeach
                    <tr class="table-dark text-white">
                        <td colspan="3"><strong>TOTAL PASIVA</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($totalPasiva->saldo_awal) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($totalPasiva->mut_debet) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($totalPasiva->mut_kredit) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($totalPasiva->saldo_akhir) }}</strong></td>
                    </tr>
                    <tr><td colspan="7"></td></tr>

                    @foreach($rugiLaba as $row)
                        <tr class="{{ $row->LINE_BALANCE === 'HEADING' ? 'table-secondary fw-bold' : '' }}">
                            <td>{{ $row->LINE_BALANCE }}</td>
                            <td>{{ $row->kode_rekening }}</td>
                            <td>{{ $row->nama_rekening }}</td>
                            <td class="text-end">{{ $fmtPlain($row->saldo_awal) }}</td>
                            <td class="text-end">{{ $fmtPlain($row->mut_debet) }}</td>
                            <td class="text-end">{{ $fmtPlain($row->mut_kredit) }}</td>
                            <td class="text-end">{{ $fmtPlain($row->saldo_akhir) }}</td>
                        </tr>
                    @endforeach

                    @foreach($admin as $row)
                        <tr class="{{ $row->LINE_BALANCE === 'HEADING' ? 'table-secondary fw-bold' : '' }}">
                            <td>{{ $row->LINE_BALANCE }}</td>
                            <td>{{ $row->kode_rekening }}</td>
                            <td>{{ $row->nama_rekening }}</td>
                            <td class="text-end">{{ $fmtPlain($row->saldo_awal) }}</td>
                            <td class="text-end">{{ $fmtPlain($row->mut_debet) }}</td>
                            <td class="text-end">{{ $fmtPlain($row->mut_kredit) }}</td>
                            <td class="text-end">{{ $fmtPlain($row->saldo_akhir) }}</td>
                        </tr>
                    @endforeach

                    <tr class="table-dark text-white">
                        <td colspan="3"><strong>SHU OPERASIONAL</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($shuOps->saldo_awal) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($shuOps->mut_debet) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($shuOps->mut_kredit) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($shuOps->saldo_akhir) }}</strong></td>
                    </tr>
                    <tr class="table-dark text-white">
                        <td colspan="3"><strong>SHU NON OPERASIONAL</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain(abs($shuNonOps->saldo_awal)) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain(abs($shuNonOps->mut_debet)) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain(abs($shuNonOps->mut_kredit)) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain(abs($shuNonOps->saldo_akhir)) }}</strong></td>
                    </tr>
                    <tr class="table-dark text-white">
                        <td colspan="3"><strong>SHU TAHUN BERJALAN SEBELUM PAJAK</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($shuSebelumPajak->saldo_awal) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($shuSebelumPajak->mut_debet) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($shuSebelumPajak->mut_kredit) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($shuSebelumPajak->saldo_akhir) }}</strong></td>
                    </tr>
                    <tr class="table-dark text-white">
                        <td colspan="3"><strong>ESTIMASI TAKSIRAN PAJAK PENGHASILAN</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($estimasiPajak->saldo_awal) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($estimasiPajak->mut_debet) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($estimasiPajak->mut_kredit) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($estimasiPajak->saldo_akhir) }}</strong></td>
                    </tr>
                    <tr class="table-dark text-white">
                        <td colspan="3"><strong>SHU TAHUN BERJALAN SETELAH PAJAK</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($shuSetelahPajak->saldo_awal) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($shuSetelahPajak->mut_debet) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($shuSetelahPajak->mut_kredit) }}</strong></td>
                        <td class="text-end"><strong>{{ $fmtPlain($shuSetelahPajak->saldo_akhir) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
