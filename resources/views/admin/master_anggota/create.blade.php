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
    <form id="form-tambah-anggota">
        @csrf
        @method('post')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Kode Unit <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="unit" id="unit"
                                            value="{{ Auth()->user()->id }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">CIF <span class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="cif" id="cif"
                                            value="001250218" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Kode AO <span class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <select name="cao" id="cao" class="form-control">
                                            <option value="">-- Pilih AO --</option>
                                            @foreach ($ao as $item)
                                                <option value="{{ $item->cao }}">{{ $item->nama_ao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Kode Kelompok <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <select name="code_kel" id="code_kel" class="form-control">
                                            <option value="">-- Pilih Kelompok --</option>
                                            @foreach ($kelompok as $item)
                                                <option value="{{ $item->code_kel }}">{{ $item->nama_kel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Nama Ketua Kel <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="cif" value="" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Telp Ketua Kel <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="no_tlp" value="" readonly>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">No Identitas <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="ktp" id="nikInput"
                                                placeholder="Masukkan No Identitas" required>
                                            {{-- <input type="text" class="form-control" name="ktp" id="ktp" placeholder="Masukkan No Identitas" required> --}}


                                            <div class="input-group-append">
                                                <a href="#" class="btn btn-primary" onclick="searchByNik()"
                                                    id="nikInput">
                                                    <i class="fas fa-search"></i>
                                                </a>
                                                {{-- <a href="#" class="btn btn-primary" id="nikInput">
                                                <i class="fas fa-search"></i>
                                            </a> --}}
                                            </div>

                                        </div>
                                        <span class="help-block with-errors text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Nama <span class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="nama" id="nama"
                                            placeholder="Masukkan Nama" required>
                                        <span class="help-block with-errors text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Tempat Lahir <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir"
                                            placeholder="Masukkan Tempat Lahir" required>
                                        <span class="help-block with-errors text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Alamat KTP</label>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Alamat <span class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <textarea name="alamat" class="form-control" id="alamat" cols="5" rows="3"
                                            placeholder="Masukkan Alamat" required></textarea>
                                        <span class="help-block with-errors text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">RT/RW <span class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="rtrw" id="rtrw"
                                            placeholder="Masukkan RT/RW" required>
                                        <span class="help-block with-errors text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Kelurahan/Desa <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="desa" id="desa"
                                            placeholder="Masukkan Kelurahan/Desa" required>
                                        <span class="help-block with-errors text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Kecamatan <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="kecamatan" id="kecamatan"
                                            placeholder="Masukkan Kecamatan" required>
                                        <span class="help-block with-errors text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Kabupaten <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="kota" id="kota"
                                            placeholder="Masukkan Kabupaten" required>
                                        <span class="help-block with-errors text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Kode Pos <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="kota" id="kota"
                                            placeholder="Masukkan Kode POS" required>
                                        <span class="help-block with-errors text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-10 col-form-label">Alamat Domisili <br>
                                        <span class="text-danger"> *abaikan jika alamat sama dengan alamat KTP</span>
                                    </label>
                                </div>

                                {{-- <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Alamat</span>
                                <div class="col-sm-7">
                                    <textarea name="alamat" class="form-control" id="alamat" cols="5" rows="3" placeholder="Masukkan Alamat"></textarea>
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>
        
                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">RT/RW</span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="rtrw" id="rtrw">
                                </div>
                            </div>
        
                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kelurahan/Desa</span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="desa" id="desa">
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>
        
                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kecamatan</span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="kecamatan" id="kecamatan">
                                </div>
                            </div>
        
                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kabupaten</span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="kota" id="kota">
                                </div>
                            </div>
        
                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kode Pos</span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="kode_pos" id="kode_pos">
                                </div>
                            </div> --}}

                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Status Perkawinan <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <select name="status_menikah" id="status_menikah" class="form-control" required>
                                            <option hidden value="">-- Pilih Status Perkawinan --</option>
                                            <option value="Menikah">Menikah</option>
                                            <option value="Belum Menikah">Belum Menikah</option>
                                            <option value="Janda">Janda</option>
                                        </select>
                                        <span class="help-block with-errors text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Agama <span class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <select name="agama" id="agama" class="form-control" required>
                                            <option hidden value="">-- Pilih Agama --</option>
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen">Kristen</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Buddha">Buddha</option>
                                            <option value="Konghucu">Konghucu</option>
                                        </select>
                                        <span class="help-block with-errors text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Pendidikan <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <select name="pendidikan" id="pendidikan" class="form-control" required>
                                            <option hidden value="">-- Pilih Pendidikan --</option>
                                            <option value="SD">SD</option>
                                            <option value="SMP">SMP</option>
                                            <option value="SMA">SMA</option>
                                        </select>
                                        <span class="help-block with-errors text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Kewarganegaraan <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <select name="kewarganegaraan" id="kewarganegaraan" class="form-control"
                                            required>
                                            <option hidden value="">-- Pilih Kewarganegaraan --</option>
                                            <option value="Indonesia">Indonesia</option>
                                        </select>
                                        <span class="help-block with-errors text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Nama Pasangan <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="waris" id="waris"
                                            required>
                                        <span class="help-block with-errors text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">No. Telp <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="no_hp" id="no_hp"
                                            placeholder="Masukkan No. Telp">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">No. Telp Pasangan <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="hp_pasangan" id="hp_pasangan"
                                            placeholder="Masukkan No. Telp Pasangan">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Nama Ibu Kandung <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="ibu_kandung" id="ibu_kandung"
                                            placeholder="Masukkan Ibu Kandung">
                                    </div>
                                </div>

                                <div class="form-group row" id="resultContainer">
                                    {{-- <div class="form-group row" id="ktpResult"> --}}
                                    {{-- untuk memunculkan data KTP dan KK --}}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#code_kel").change(function() {
                var code_kel = $(this).val();
                console.log("Code Kelompok Terpilih:", code_kel); // Debug

                if (code_kel !== "") {
                    $.ajax({
                        url: "/get-kelompok",
                        type: "GET",
                        data: {
                            code_kel: code_kel
                        },
                        success: function(response) {
                            console.log("Response dari Server:", response); // Debug
                            $("#cao").val(response.cao);
                            $("#no_tlp").val(response.no_tlp);
                        }
                    });
                } else {
                    $("#cao").val("");
                    $("#no_tlp").val("");
                }
            });
        });

        function searchByNik() {
            const nik = document.getElementById('nikInput').value;
            const resultContainer = document.getElementById('resultContainer');

            // Kosongkan hasil sebelumnya
            resultContainer.innerHTML = '';

            if (!nik) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'NIK harus diisi!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Lakukan request ke backend
            fetch(`http://mobcol.nurinsani.co.id/apimobcol/rmcKtp.php?ktp=${nik}`)
                // fetch(`/proxy/search?ktp=${nik}`)
                // fetch(`http://localhost/data-anggota-api/public/api/files/${nik}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Data tidak ditemukan');
                    }
                    return response.json();
                })
                .then(data => {
                    // Tampilkan hasil pencarian
                    resultContainer.innerHTML = `

                <div class="col-sm-11">
                        <div class="card mb-3">
                            <div class="card-header">KTP</div>
                            <div class="card-body">
                                <img src="http://rmc.nurinsani.co.id:9373/berkas/${data.data[0].ktp}" width="550px" alt="Gambar KTP">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-11">
                        <div class="card mb-3">
                            <div class="card-header">KTP</div>
                            <div class="card-body">
                                <img src="http://rmc.nurinsani.co.id:9373/berkas/${data.data[0].kk}" width="550px" alt="Gambar KTP">
                            </div>
                        </div>
                    </div>
                `;


                })
                .catch(error => {
                    // Tampilkan pesan error
                    resultContainer.innerHTML = `<p style="color: red;">${error.message}</p>`;
                });
        }

        $(document).ready(function() {
            // Handle form submission
            $('#form-tambah-anggota').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit default

                // Kirim data menggunakan AJAX
                $.ajax({
                    url: "{{ route('anggota.store') }}",
                    type: "POST",
                    data: $(this).serialize(), // Serialize form data
                    success: function(response) {
                        // Tampilkan pesan sukses
                        Swal.fire({
                            title: 'Sukses!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect ke halaman index setelah simpan
                                window.location.href = "{{ route('anggota.index') }}";
                            }
                        });
                    },
                    error: function(xhr) {
                        // Tampilkan pesan error
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.message ||
                                'Terjadi kesalahan saat menyimpan data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
