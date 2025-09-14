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
    <div class="card">

        <div class="card-body">
            <a href="" class="btn btn-sm btn-success">Excel</a>
            <table class="table table-bordered text-right">
                <thead class="bg-primary text-white text-center">
                    <tr>
                        <th>Jenis Akun</th>
                        @foreach ($report_ekuitas as $ekuitas)
                            <th>{{ $ekuitas['jenis_account'] }}</th>
                        @endforeach
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-left"><strong>Saldo Awal</strong></td>
                        @foreach ($report_ekuitas as $ekuitas)
                            <td>{{ number_format($ekuitas['saldo_awal'], 0, ',', '.') }}</td>
                        @endforeach
                        <td><strong>{{ number_format(collect($report_ekuitas)->sum('saldo_awal'), 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-left"><strong>Penambahan (Pengurangan)</strong></td>
                        @foreach ($report_ekuitas as $ekuitas)
                            <td>
                                @if ($ekuitas['penambahan'] == 0)
                                    -
                                @else
                                    {{ number_format($ekuitas['penambahan'], 0, ',', '.') }}
                                @endif
                            </td>
                        @endforeach
                        <td><strong>{{ number_format(collect($report_ekuitas)->sum('penambahan'), 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-left"><strong>Saldo Akhir</strong></td>
                        @foreach ($report_ekuitas as $ekuitas)
                            <td><strong>{{ number_format($ekuitas['saldo_akhir'], 0, ',', '.') }}</strong></td>
                        @endforeach
                        <td><strong>{{ number_format(collect($report_ekuitas)->sum('saldo_akhir'), 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
