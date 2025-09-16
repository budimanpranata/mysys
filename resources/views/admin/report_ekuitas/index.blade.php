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

            {{-- Form pilih bulan --}}
            {{-- <form action="" method="GET" class="form-inline mb-3">
                <label for="bulan" class="mr-2">Pilih Bulan:</label>
                <select name="bulan" id="bulan" class="form-control mr-2">
                    @foreach (range(1, 12) as $month)
                        <option value="{{ $month }}" {{ request('bulan') == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <select name="tahun" id="tahun" class="form-control mr-2">
                    @foreach (range(date('Y') - 5, date('Y')) as $year)
                        <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </form> --}}

            {{-- Tombol Export Excel --}}
            <a href="{{ route('report.ekuitas.export') }}"
                class="btn btn-sm btn-success mb-3">Export Excel</a>

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
