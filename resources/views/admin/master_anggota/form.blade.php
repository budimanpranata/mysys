<form action="{{ route('anggota.store') }}" method="post">
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
                                        value="" readonly>
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
                                        <input type="text" class="form-control" name="ktp" id="nikInput"
                                            placeholder="Masukkan No Identitas">

                                        <div class="input-group-append">
                                            <a href="#" class="btn btn-primary" onclick="searchByNik()"
                                                id="nikInput">
                                                <i class="fas fa-search"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Nama <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" id="nama"
                                        placeholder="Masukkan Nama">
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Tempat Lahir <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir"
                                        placeholder="Masukkan Tempat Lahir">
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Tanggal Lahir <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir"
                                        placeholder="Masukkan Tempat Lahir">
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
                                        placeholder="Masukkan Alamat"></textarea>
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">RT/RW <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="rtrw" id="rtrw"
                                        placeholder="Masukkan RT/RW">
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kelurahan/Desa <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="desa" id="desa"
                                        placeholder="Masukkan Kelurahan/Desa">
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kecamatan <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="kecamatan" id="kecamatan"
                                        placeholder="Masukkan Kecamatan">
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kabupaten <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="kota" id="kota"
                                        placeholder="Masukkan Kabupaten">
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kode Pos <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="kode_pos" id="kode_pos"
                                        placeholder="Masukkan Kode POS">
                                    <span class="help-block with-errors text-danger"></span>
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
                                    <select name="status_menikah" id="status_menikah" class="form-control">
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
                                    <select name="agama" id="agama" class="form-control">
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
                                    <select name="pendidikan" id="pendidikan" class="form-control">
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
                                    >
                                        <option hidden value="">-- Pilih Kewarganegaraan --</option>
                                        <option selected value="Indonesia">Indonesia</option>
                                    </select>
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Nama Pasangan <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="waris" id="waris"
                                        placeholder="Masukkan Nama Pasangan">
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Nama Pasangan <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="pekerjaan_pasangan" id="pekerjaan_pasangan"
                                        placeholder="Masukkan Pekerjaan Pasangan">
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

                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
            
        </div>
    </div>
</form>