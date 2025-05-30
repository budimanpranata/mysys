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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>KTP</th>
                                <th>Unit</th>
                                <th>Kode Kel</th>
                                <th>Norek</th>
                                <th>CIF</th>
                                <th>Nama</th>
                                <th>Pembiayaan</th>
                                <th>Saldo Pembiayaan</th>
                                <th>Saldo Margin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <script>

        let table;

        $(function() {
            table = $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('viewData.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'ktp'
                    },
                    {
                        data: 'unit'
                    },
                    {
                        data: 'kode_kel_anggota'
                    },
                    {
                        data: 'norek'
                    },
                    {
                        data: 'cif_anggota'
                    },
                    {
                        data: 'nama_anggota'
                    },
                    {
                        data: 'plafond_pembiayaan'
                    },
                    {
                        data: 'os_pembiayaan'
                    },
                    {
                        data: 'saldo_margin_pembiayaan'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ]
            })
        });

        document.addEventListener('DOMContentLoaded', function() {
            const uppercaseElements = document.querySelectorAll('.uppercase');

            uppercaseElements.forEach(function(element) {
                element.addEventListener('input', function() {
                    element.value = element.value.toUpperCase();
                });
            });
        });

        $(document).ready(function() {
            $('#cif').change(function() {
                var cif = $(this).val();

                if (cif) {
                    $.ajax({
                        url: '/get-anggota/' + cif,
                        type: 'GET',
                        success: function(response) {
                            if (response.success) {
                                $('#no_tlp').val(response.data.no_hp);
                            } else {
                                $('#no_tlp').val('');
                                alert(response.message);
                            }
                        },
                        error: function() {
                            alert('Terjadi kesalahan saat memuat data.');
                        }
                    });
                } else {
                    $('#no_tlp').val('');
                }
            });
        });

    </script>
@endpush
