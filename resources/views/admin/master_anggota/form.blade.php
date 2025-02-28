<form action="{{ route('anggota.store') }}" method="post" id="form">
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
                                        value="{{ auth()->check() ? auth()->user()->id : 'User Belum Login' }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">CIF <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="cif" id="cif"
                                        value="" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kode AO <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <select name="cao" id="cao" class="form-control @error('cao') is-invalid @enderror">
                                        <option value="">-- Pilih AO --</option>
                                        @foreach ($ao as $item)
                                            <option value="{{ $item->cao }}">{{ $item->nama_ao }}</option>
                                        @endforeach
                                    </select>
                                    @error('cao')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kode Kelompok <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <select name="kode_kel" id="kode_kel" value="{{ old('kode_kel') }}" class="form-control @error('kode_kel') is-invalid @enderror">
                                        <option value="">-- Pilih Kelompok --</option>
                                        @foreach ($kelompok as $item)
                                            <option value="{{ $item->code_kel }}">{{ $item->nama_kel }}</option>
                                        @endforeach
                                    </select>
                                    @error('kode_kel')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Nama Ketua Kel <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="nama_ao" value="" readonly>
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
                                        <input type="text" class="form-control @error('ktp') is-invalid @enderror" name="ktp" value="{{ old('ktp') }}" id="nikInput"
                                            placeholder="Masukkan No Identitas">
                                            
                                            <div class="input-group-append">
                                                <a href="#" class="btn btn-primary" onclick="cariKtp()"
                                                id="nikInput">
                                                <i class="fas fa-search"></i>
                                            </a>
                                        </div>
                                        @error('ktp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Nama <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" name="nama" id="nama"
                                        placeholder="Masukkan Nama">
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Tempat Lahir <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" name="tempat_lahir" id="tempat_lahir"
                                        placeholder="Masukkan Tempat Lahir">
                                        @error('tempat_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Tanggal Lahir <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" name="tgl_lahir" id="tgl_lahir"
                                        placeholder="Masukkan Tempat Lahir">
                                        @error('tgl_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row text-center" style="border-bottom: 2px solid black; width: 93%;">
                                <label class="col-sm-4 col-form-label mx-auto">Isi alamat sesuai KTP</label>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Alamat <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat" cols="5" rows="3"
                                        placeholder="Masukkan Alamat"></textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">RT/RW <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('rtrw') is-invalid @enderror" name="rtrw" id="rtrw"
                                        placeholder="Masukkan RT/RW">
                                        @error('rtrw')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kelurahan/Desa <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('desa') is-invalid @enderror" name="desa" id="desa"
                                        placeholder="Masukkan Kelurahan/Desa">
                                        @error('desa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kecamatan <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('kecamatan') is-invalid @enderror" name="kecamatan" id="kecamatan"
                                        placeholder="Masukkan Kecamatan">
                                        @error('kecamatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kabupaten <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('kota') is-invalid @enderror" name="kota" id="kota"
                                        placeholder="Masukkan Kabupaten">
                                        @error('kota')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kode Pos <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('kode_pos') is-invalid @enderror" name="kode_pos" id="kode_pos"
                                        placeholder="Masukkan Kode POS">
                                        @error('kode_pos')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-10 col-form-label">Alamat Domisili <br>
                                    <span class="text-danger"> *abaikan jika alamat sama dengan alamat KTP</span>
                                </label>
                            </div>

                            {{-- buat form alamat berdasarkan domisili, 
                            jika alamat domisili sesuai dengan alamat ktp maka bisa di hide dengan javascript --}}

                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Status Perkawinan <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <select name="status_menikah" id="status_menikah" class="form-control @error('status_menikah') is-invalid @enderror">
                                        <option hidden value="">-- Pilih Status Perkawinan --</option>
                                        <option value="Menikah">Menikah</option>
                                        <option value="Belum Menikah">Belum Menikah</option>
                                        <option value="Janda">Janda</option>
                                    </select>
                                    @error('status_menikah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Agama <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <select name="agama" id="agama" class="form-control @error('agama') is-invalid @enderror">
                                        <option hidden value="">-- Pilih Agama --</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                    @error('agama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Pendidikan <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <select name="pendidikan" id="pendidikan" class="form-control @error('pendidikan') is-invalid @enderror">
                                        <option hidden value="">-- Pilih Pendidikan --</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA">SMA</option>
                                    </select>
                                    @error('pendidikan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kewarganegaraan <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <select name="kewarganegaraan" id="kewarganegaraan" class="form-control @error('kewarganegaraan') is-invalid @enderror"
                                    >
                                        <option hidden value="">-- Pilih Kewarganegaraan --</option>
                                        <option selected value="Indonesia">Indonesia</option>
                                    </select>
                                    @error('kewarganegaraan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Nama Pasangan <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('waris') is-invalid @enderror" name="waris" id="waris"
                                        placeholder="Masukkan Nama Pasangan">
                                        @error('waris')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Pekerjaan Pasangan <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('pekerjaan_pasangan') is-invalid @enderror" name="pekerjaan_pasangan" id="pekerjaan_pasangan"
                                        placeholder="Masukkan Pekerjaan Pasangan">
                                        @error('pekerjaan_pasangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">No. Telp <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp" id="no_hp"
                                        placeholder="Masukkan No. Telp">
                                        @error('no_hp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">No. Telp Pasangan <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('hp_pasangan') is-invalid @enderror" name="hp_pasangan" id="hp_pasangan"
                                        placeholder="Masukkan No. Telp Pasangan">
                                        @error('hp_pasangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Nama Ibu Kandung <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('ibu_kandung') is-invalid @enderror" name="ibu_kandung" id="ibu_kandung"
                                        placeholder="Masukkan Ibu Kandung">
                                        @error('ibu_kandung')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row" id="resultContainer">

                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Kembali</button>
                </div>
            </div>
            
        </div>
    </div>
</form>