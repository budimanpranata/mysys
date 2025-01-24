<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form action="" method="post">
            @csrf
            @method('post')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kode Unit <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="unit" id="unit" placeholder="Masukkan Kode Unit">
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">CIF <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="cif" id="cif" placeholder="Masukkan CIF">
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kode AO <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" name="cao" id="cao" placeholder="Masukkan Kode AO">
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kode Kelompok <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" name="code_kel" id="code_kel" placeholder="Masukkan Kode AO">
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Nama Ketua Kel <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="" id="" value="Agus" placeholder="" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Telp Ketua Kel <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" name="" id="" placeholder="" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">No Identitas <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="ktp" id="ktp" placeholder="Masukkan No Identitas" required>
                                        
                                        <div class="input-group-append">
                                            <a href="#" class="btn btn-primary" id="searchLink">
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
                                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Masukkan Nama" required>
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Tempat Lahir <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" placeholder="Masukkan Tempat Lahir" required>
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Alamat KTP</label>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Alamat <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <textarea name="alamat" class="form-control" id="alamat" cols="5" rows="3" placeholder="Masukkan Alamat" required></textarea>
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">RT/RW <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="rtrw" id="rtrw" placeholder="Masukkan RT/RW" required>
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kelurahan/Desa <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="desa" id="desa" placeholder="Masukkan Kelurahan/Desa" required>
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kecamatan <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="kecamatan" id="kecamatan" placeholder="Masukkan Kecamatan" required>
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kabupaten <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="kota" id="kota" placeholder="Masukkan Kabupaten" required>
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kode Pos <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="kota" id="kota" placeholder="Masukkan Kode POS" required>
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-10 col-form-label">Alamat Domisili <br>
                                    <span class="text-danger"> *abaikan jika alamat sama dengan alamat KTP</span>
                                </label>
                            </div>

                            <div class="form-group row">
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
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Status Perkawinan <span class="text-danger">*</span></span>
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
                                <span class="col-sm-4 col-form-label">Pendidikan <span class="text-danger">*</span></span>
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
                                <span class="col-sm-4 col-form-label">Kewarganegaraan <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <select name="kewarganegaraan" id="kewarganegaraan" class="form-control" required>
                                        <option hidden value="">-- Pilih Kewarganegaraan --</option>
                                        <option value="Indonesia">Indonesia</option>
                                    </select>
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Nama Pasangan <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="waris" id="waris" required>
                                    <span class="help-block with-errors text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">No. Telp <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="Masukkan No. Telp">
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">No. Telp Pasangan <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="hp_pasangan" id="hp_pasangan" placeholder="Masukkan No. Telp Pasangan">
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Nama Ibu Kandung <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="ibu_kandung" id="ibu_kandung" placeholder="Masukkan Ibu Kandung">
                                </div>
                            </div>

                            <div class="form-group row" id="ktpResult">
                                {{-- untuk memunculkan data KTP dan KK --}}
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
    </div>
</div>
