@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Selamat Datang, {{ Auth::user()->name }}</h1>
                <h5 class="mt-3">Tanggal SysDate : {{ Auth::user()->param_tanggal }}</h5>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Cetak Cs</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
@endsection

@section('content')
    <div class="card">

        <div class="card-header">
            <h3 class="card-title">Cetak Cs</h3>
        </div>

        <div class="card-body">

            <div class="container mt-5">
                <h3>Cetak Cs</h3>
                <div class="card p-4 mt-0">

                    <div class="mb-3">
                        <label for="kodeAo">Cari Nama AO:</label>
                        <select id="kodeAo" class="form-control" style="width: 50%;">

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggalTagih" class="form-label">Tanggal Cs</label>
                        <input type="date" class="form-control" id="tanggalTagih" value="">
                    </div>
                    <p>
                        <button id="submit-kode" class="btn btn-primary mt-2">Cari</button>



                    </p>

                    {{-- <div class="mt-4">
                        <h3>Preview PDF</h3>
                        <iframe id="pdfViewer" style="width: 100%; height: 600px;" frameborder="0"></iframe>
                    </div> --}}

                </div>
            </div>


        </div>
        <!-- /.card-body -->

    </div>
    <!-- /.card -->


    @include('sweetalert::alert')

    <script>
        $(document).ready(function() {
            console.log('Halaman telah dimuat.');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('cetak.kode.ao') }}', // Endpoint untuk mendapatkan data Kode Ao
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Tambahkan opsi ke dropdown
                    let kodeAoDropdown = $('#kodeAo');
                    kodeAoDropdown.append('<option value="" disabled selected>Pilih Kode Ao</option>');
                    $.each(data, function(index, item) {
                        kodeAoDropdown.append('<option value="' + item.id + '">' + item.text +
                            '</option>');
                    });
                },
                error: function() {
                    Swal.fire('Gagal', 'Gagal memuat data Kode AO!', 'error');
                },
            });

            // Event submit
            $('#submit-kode').on('click', function() {
                const kodeAo = $('#kodeAo').val();

                if (kodeAo) {
                    window.location.href = '{{ route('pdfCs') }}?kodeAo=' + kodeAo;
                    const tanggalTagih = $('#tanggalTagih').val();
                    window.location.href = '{{ route('pdfCs') }}?kodeAo=' + kodeAo + '&tanggalTagih=' +
                        tanggalTagih;
                    // const pdfUrl = '{{ route('pdfCs') }}?kodeAo=' + kodeAo;

                    // // Tampilkan PDF di iframe
                    // $('#pdfViewer').attr('src', pdfUrl);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Kode AO',
                        text: 'Silakan pilih kode AO terlebih dahulu!',
                    });
                }
            });
        });
    </script>
@endsection
