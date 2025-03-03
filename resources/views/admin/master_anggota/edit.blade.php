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
    @include('admin.master_anggota.form-edit')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Ketika dropdown kelompok dipilih
            $('#kode_kel').change(function() {
                // Ambil nilai yang dipilih
                var selectedCode = $(this).val();
                var selectedCodeString = selectedCode ? selectedCode.toString() : '';
                console.log("Kode Kelompok yang dikirim:", selectedCodeString); // Debug

                if (selectedCode) {
                    $.ajax({
                        url: '/get-kelompok-data',
                        type: 'GET',
                        data: { code_kel: selectedCodeString },
                        success: function(response) {
                            console.log("Response dari Server:", response); // Debug
                            $('#nama_ao').val(response.nama_ao);
                            $('#no_tlp').val(response.no_tlp);
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    // Kosongkan input fields jika tidak ada kelompok yang dipilih
                    $('#nama_ao').val('');
                    $('#no_tlp').val('');
                }
            });
        });
    </script>
@endpush
