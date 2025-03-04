<form action="{{ route('anggota.update', $anggota->no) }}" method="post" id="form">
    @csrf
    @method('put')
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
                                        value="{{ auth()->check() ? auth()->user()->unit : 'User Belum Login' }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">CIF <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="cif" value="{{ $anggota->cif }}" id="cif"
                                        value="" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Kode AO <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <select name="cao" id="cao" class="form-control @error('cao') is-invalid @enderror">
                                        <option value="">-- Pilih AO --</option>
                                        @foreach ($ao as $item)
                                            <option value="{{ $item->cao }}"
                                                @if ($item->cao == $anggota->cao)
                                                    selected
                                                @endif
                                                >{{ $item->nama_ao }}</option>
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
                                    <select name="kode_kel" id="kode_kel" value="{{ old('kode_kel', $anggota->kode_kel) }}" class="form-control @error('kode_kel') is-invalid @enderror">
                                        <option value="">-- Pilih Kelompok --</option>
                                        @foreach ($kelompok as $item)
                                            <option value="{{ $item->code_kel }}"
                                                @if ($item->kode_kel == $anggota->code_kel)
                                                    selected
                                                @endif
                                                >{{ $item->nama_kel }}</option>
                                        @endforeach
                                    </select>
                                    @error('kode_kel')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">No Identitas <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('ktp') is-invalid @enderror" name="ktp" value="{{ old('ktp', $anggota->ktp) }}" id="nikInput"
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
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $anggota->nama) }}" name="nama" id="nama"
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
                                    <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir', $anggota->tempat_lahir) }}" name="tempat_lahir" id="tempat_lahir"
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
                                    <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" value="{{ old('tgl_lahir', $anggota->tgl_lahir) }}" name="tgl_lahir" id="tgl_lahir"
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
                                        placeholder="Masukkan Alamat">{{ old('alamat', $anggota->alamat) }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">RT/RW <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('rtrw') is-invalid @enderror" value="{{ old('rtrw', $anggota->rtrw) }}" name="rtrw" id="rtrw"
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
                                    <input type="text" class="form-control @error('desa') is-invalid @enderror" value="{{ old('desa', $anggota->desa) }}" name="desa" id="desa"
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
                                    <input type="text" class="form-control @error('kecamatan') is-invalid @enderror" value="{{ old('kecamatan', $anggota->kecamatan) }}" name="kecamatan" id="kecamatan"
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
                                    <input type="text" class="form-control @error('kota') is-invalid @enderror" value="{{ old('kota', $anggota->kota) }}" name="kota" id="kota"
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
                                    <input type="text" class="form-control @error('kode_pos') is-invalid @enderror" value="{{ old('kode_pos', $anggota->kode_pos) }}" name="kode_pos" id="kode_pos"
                                        placeholder="Masukkan Kode POS">
                                        @error('kode_pos')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Status Perkawinan <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <select name="status_menikah" id="status_menikah" class="form-control @error('status_menikah') is-invalid @enderror">
                                        <option hidden value="">-- Pilih Status Perkawinan --</option>
                                        <option value="Menikah"
                                        @if ($anggota->status_menikah = 'Menikah')
                                            selected
                                        @endif
                                        >Menikah</option>
                                        <option value="Belum Menikah"
                                        @if ($anggota->status_menikah = 'Belum Menikah')
                                            selected
                                        @endif
                                        >Belum Menikah</option>
                                        <option value="Janda"
                                        @if ($anggota->status_menikah = 'Janda')
                                            selected
                                        @endif
                                        >Janda</option>
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
                                        <option value="Islam"
                                        @if ($anggota->agama = 'Islam')
                                            selected
                                        @endif
                                        >Islam</option>
                                        <option value="Kristen"
                                        @if ($anggota->agama = 'Kristen')
                                            selected
                                        @endif
                                        >Kristen</option>
                                        <option value="Hindu"
                                        @if ($anggota->agama = 'Hindu')
                                                    selected
                                                @endif
                                        >Hindu</option>
                                        <option value="Buddha"
                                        @if ($anggota->agama = 'Buddha')
                                            selected
                                        @endif
                                        >Buddha</option>
                                        <option value="Konghucu"
                                        @if ($anggota->agama = 'Konghucu')
                                            selected
                                        @endif
                                        >Konghucu</option>
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
                                        <option value="SD"
                                        @if ($anggota->pendidikan = 'SD')
                                            selected
                                        @endif
                                        >SD</option>
                                        <option value="SMP"
                                        @if ($anggota->pendidikan = 'SMP')
                                            selected
                                        @endif
                                        >SMP</option>
                                        <option value="SMA"
                                        @if ($anggota->pendidikan = 'SMA')
                                            selected
                                        @endif
                                        >SMA</option>
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
                                    <input type="text" class="form-control @error('waris') is-invalid @enderror" value="{{ old('waris', $anggota->waris) }}" name="waris" id="waris"
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
                                    <input type="text" class="form-control @error('pekerjaan_pasangan') is-invalid @enderror" value="{{ old('pekerjaan_pasangan', $anggota->pekerjaan_pasangan) }}" name="pekerjaan_pasangan" id="pekerjaan_pasangan"
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
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $anggota->no_hp) }}" name="no_hp" id="no_hp"
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
                                    <input type="text" class="form-control @error('hp_pasangan') is-invalid @enderror" value="{{ old('hp_pasangan', $anggota->hp_pasangan) }}" name="hp_pasangan" id="hp_pasangan"
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
                                    <input type="text" class="form-control @error('ibu_kandung') is-invalid @enderror" value="{{ old('ibu_kandung', $anggota->ibu_kandung) }}" name="ibu_kandung" id="ibu_kandung"
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