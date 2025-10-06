@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Report Ekuitas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Report Ekuitas</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <form id="formReportEkuitas" action="{{ route('report.ekuitas') }}" method="POST">
                @csrf

                <div class="form-group row" id="bulan-tahun-row">
                    <label class="col-sm-3 col-form-label">Jenis Report</label>
                    <div class="col-sm-9">
                        <select name="jenis_pull" class="form-control" required>
                            <option value="">-- Jenis Report --</option>
                            <option value="01">EOM</option>
                            <option value="02">Current</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Bulan & Tahun</label>
                    <div class="col-sm-4">
                        <select name="jenis_transaksi" id="jenis_transaksi" class="form-control">
                            <option value="">-- Bulan --</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <select name="tahun" id="tahun" class="form-control">
                            <option value="">-- Tahun --</option>
                            @for ($y = date('Y'); $y >= 2012; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>

    @if(!empty($data))
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" class="align-middle text-center">{{ $tanggal_display }}</th>
                            <th class="text-center">Simpanan Pokok</th>
                            <th class="text-center">Simpanan Wajib</th>
                            <th class="text-center">Hibah</th>
                            <th class="text-center">Cadangan</th>
                            <th class="text-center">Akumulasi<br>Sisa Hasil Usaha</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Saldo Awal</strong></td>
                            <td class="text-end">{{ number_format($data->simpanan_pokok_awal, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($data->simpanan_wajib_awal, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $data->hibah_awal > 0 ? number_format($data->hibah_awal, 0, ',', '.') : '-' }}</td>
                            <td class="text-end">{{ number_format($data->cadangan_awal, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($data->shu_awal, 0, ',', '.') }}</td>
                            <td class="text-end"><strong>{{ number_format($total_saldo_awal, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Penambahan (Pengurangan)</strong></td>
                            <td class="text-end">{{ $data->simpanan_pokok_penambahan != 0 ? number_format($data->simpanan_pokok_penambahan, 0, ',', '.') : '-' }}</td>
                            <td class="text-end">{{ $data->simpanan_wajib_penambahan != 0 ? number_format($data->simpanan_wajib_penambahan, 0, ',', '.') : '-' }}</td>
                            <td class="text-center">{{ $data->hibah_penambahan != 0 ? number_format($data->hibah_penambahan, 0, ',', '.') : '-' }}</td>
                            <td class="text-center">{{ $data->cadangan_penambahan != 0 ? number_format($data->cadangan_penambahan, 0, ',', '.') : '-' }}</td>
                            <td class="text-end">{{ $data->shu_penambahan != 0 ? number_format($data->shu_penambahan, 0, ',', '.') : '-' }}</td>
                            <td class="text-end"><strong>{{ number_format($total_penambahan, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr class="table-secondary">
                            <td><strong>Saldo Akhir</strong></td>
                            <td class="text-end"><strong>{{ number_format($data->simpanan_pokok_akhir, 0, ',', '.') }}</strong></td>
                            <td class="text-end"><strong>{{ number_format($data->simpanan_wajib_akhir, 0, ',', '.') }}</strong></td>
                            <td class="text-center"><strong>{{ $data->hibah_akhir > 0 ? number_format($data->hibah_akhir, 0, ',', '.') : '-' }}</strong></td>
                            <td class="text-end"><strong>{{ number_format($data->cadangan_akhir, 0, ',', '.') }}</strong></td>
                            <td class="text-end"><strong>{{ number_format($data->shu_akhir, 0, ',', '.') }}</strong></td>
                            <td class="text-end"><strong>{{ number_format($total_saldo_akhir, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-center mb-3">
                <a href="{{ route('report.ekuitas.export', ['jenis' => $jenis, 'bulan' => $bulan, 'tahun' => $tahun]) }}" 
                class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>

        </div>
    @endif
@endsection


@push('scripts')

    <script>
        document.getElementById('formReportEkuitas').addEventListener('submit', function (e) {
            e.preventDefault();

            let jenisPull = document.querySelector('select[name="jenis_pull"]').value;
            let bulan = document.getElementById('jenis_transaksi').value;
            let tahun = document.getElementById('tahun').value;

            // Validasi untuk EOM
            if (jenisPull === "01") {
                if (bulan === "" || tahun === "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Untuk report EOM, Bulan dan Tahun harus dipilih!',
                    });
                    return false;
                }
            }

            Swal.fire({
                title: 'Memproses Data...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            setTimeout(() => {
                this.submit();
            }, 1000);
        });
    </script>

@endpush