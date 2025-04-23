<form action="{{ route('view-data.update', $anggota->no) }}" method="post" id="form">
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
                                    <input type="text" class="form-control" name="cif" value="{{ $anggota->cif }}" readonly>
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
                                                @if ($item->code_kel == $anggota->code_kel)
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
                                             maxlength="16" onkeypress="return hanyaAngka(event)" onkeyup="this.value = this.value.replace(/[^0-9]/g, '')"
                                             minlength="16" required
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
                                        placeholder="Masukkan Nama" style="text-transform: uppercase;" readonly>
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
                                        placeholder="Masukkan Tempat Lahir" readonly>
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
                                        placeholder="Masukkan Tempat Lahir" readonly>
                                        @error('tgl_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row text-center" style="border-bottom: 2px solid black; width: 93%;">
                                <label class="col-sm-4 col-form-label mx-auto">ISI ALAMAT SESUAI KTP</label>
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

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="sameAddress" onclick="toggleAlamatDomisili()">
                                <label class="form-check-label" for="sameAddress">
                                    ALAMAT DOMISILI SAMA DENGAN ALAMAT SESUAI
                                </label>
                            </div>
                            
                            <fieldset id="alamatDomisili">

                                <div class="form-group row text-center" style="border-bottom: 2px solid black; width: 93%;">
                                    <label class="col-sm-4 col-form-label mx-auto">ISI ALAMAT DOMISILI</label>
                                </div>

                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Alamat <span class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <textarea name="alamat_domisili" class="form-control @error('alamat_domisili') is-invalid @enderror" id="alamat_domisili" cols="5" rows="3"
                                            placeholder="Masukkan Alamat" style="text-transform: uppercase;">{{ old('alamat_domisili', $anggota_detail->alamat_domisili ?? '') }}</textarea>
                                            @error('alamat_domisili')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">RT/RW <span class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control @error('rtrw_domisili') is-invalid @enderror" name="rtrw_domisili" value="{{ old('rtrw_domisili', $anggota_detail->rtrw_domisili ?? '') }}" id="rtrw"
                                            placeholder="Masukkan RT/RW" style="text-transform: uppercase;">
                                            @error('rtrw_domisili')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Kelurahan/Desa <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control @error('desa_domisili') is-invalid @enderror" name="desa_domisili" value="{{ old('desa_domisili', $anggota_detail->desa_domisili ?? '') }}" id="desa"
                                            placeholder="Masukkan Kelurahan/Desa" style="text-transform: uppercase;">
                                            @error('desa_domisili')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Kecamatan <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control @error('kecamatan_domisili') is-invalid @enderror" name="kecamatan_domisili" value="{{ old('kecamatan_domisili', $anggota_detail->kecamatan_domisili ?? '') }}" id="kecamatan"
                                            placeholder="Masukkan Kecamatan" style="text-transform: uppercase;">
                                            @error('kecamatan_domisili')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Kabupaten <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control @error('kota_domisili') is-invalid @enderror" name="kota_domisili" value="{{ old('kota_domisili', $anggota_detail->kota_domisili ?? '') }}" id="kota"
                                            placeholder="Masukkan Kabupaten" style="text-transform: uppercase;">
                                            @error('kota_domisili')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <span class="col-sm-4 col-form-label">Kode Pos <span
                                            class="text-danger">*</span></span>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control @error('kode_pos_domisili') is-invalid @enderror" name="kode_pos_domisili" value="{{ old('kode_pos_domisili', $anggota_detail->kode_pos_domisili ?? '') }}" id="kode_pos"
                                            placeholder="Masukkan Kode POS" style="text-transform: uppercase;">
                                            @error('kode_pos_domisili')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                    </div>
                                </div>
                            </fieldset>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Status Perkawinan <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <select name="status_menikah" id="status_menikah" class="form-control @error('status_menikah') is-invalid @enderror">
                                        <option hidden value="">-- PILIH STATUS PERKAWINAN --</option>
                                        <option value="MENIKAH"
                                        @if ($anggota->status_menikah = 'MENIKAH')
                                            selected
                                        @endif
                                        >MENIKAH</option>
                                        <option value="BELUM MENIKAH"
                                        @if ($anggota->status_menikah = 'BELUM MENIKAH')
                                            selected
                                        @endif
                                        >BELUM MENIKAH</option>
                                        <option value="JANDA"
                                        @if ($anggota->status_menikah = 'JANDA')
                                            selected
                                        @endif
                                        >JANDA</option>
                                    </select>
                                    @error('status_menikah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Agama <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <select name="agama" id="agama" class="form-control @error('agama') is-invalid @enderror" disabled>
                                        <option hidden value="">-- PILIH AGAMA --</option>
                                        <option value="ISLAM"
                                        @if ($anggota->agama = 'ISLAM')
                                            selected
                                        @endif
                                        >ISLAM</option>
                                        <option value="KRISTEN"
                                        @if ($anggota->agama = 'KRISTEN')
                                            selected
                                        @endif
                                        >KRISTEN</option>
                                        <option value="HINDU"
                                        @if ($anggota->agama = 'HINDU')
                                                    selected
                                                @endif
                                        >HINDU</option>
                                        <option value="BUDDHA"
                                        @if ($anggota->agama = 'BUDDHA')
                                            selected
                                        @endif
                                        >BUDDHA</option>
                                        <option value="KONGHUCU"
                                        @if ($anggota->agama = 'KONGHUCU')
                                            selected
                                        @endif
                                        >KONGHUCU</option>
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
                                    <select name="pendidikan" id="pendidikan" class="form-control @error('pendidikan') is-invalid @enderror" disabled>
                                        <option hidden value="">-- PILIH PENDIDIKAN --</option>
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
                                    <select name="kewarganegaraan" id="kewarganegaraan" class="form-control @error('kewarganegaraan') is-invalid @enderror" disabled
                                    >
                                        <option hidden value="">-- PILIH KEWARGANEGARAAN --</option>
                                        <option selected value="INDONESIA">INDONESIA</option>
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
                                        placeholder="Masukkan Ibu Kandung" readonly>
                                        @error('ibu_kandung')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row text-center" style="border-bottom: 2px solid black; width: 93%;">
                                <label class="col-sm-4 col-form-label mx-auto">DATA PEMBIAYAAN</label>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Tanggal Wakalah <span class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control @error('tgl_wakalah') is-invalid @enderror" value="{{ old('tgl_wakalah', \Carbon\Carbon::parse($anggota->tgl_wakalah)->format('Y-m-d')) }}" name="tgl_wakalah" id="tgl_wakalah"
                                        placeholder="Masukkan Tanggal Wakalah">
                                        @error('tgl_wakalah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Tangal Jatpo <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control @error('maturity_date') is-invalid @enderror" value="{{ old('maturity_date', \Carbon\Carbon::parse($anggota->maturity_date)->format('Y-m-d')) }}" name="maturity_date" id="maturity_date"
                                        placeholder="Masukkan tanggal Jatpo">
                                        @error('maturity_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Bidang Usaha <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('nama_usaha') is-invalid @enderror" value="{{ old('nama_usaha', $anggota->nama_usaha) }}" name="nama_usaha" id="nama_usaha"
                                        placeholder="Masukkan Nama Usaha">
                                        @error('nama_usaha')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Hari Minggon <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('hari') is-invalid @enderror" value="{{ old('hari', $anggota->hari) }}" name="hari" id="hari"
                                        placeholder="Masukkan Hari Minggon">
                                        @error('hari')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <span class="col-sm-4 col-form-label">Gol Debitur <span
                                        class="text-danger">*</span></span>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('gol') is-invalid @enderror" value="{{ old('gol', $anggota->gol) }}" name="gol" id="gol"
                                        placeholder="Masukkan Gol Debitur">
                                        @error('gol')
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
                    <a href="{{ route('anggota.index') }}" class="btn btn-sm btn-danger">Kembali</a>
                </div>
            </div>
            
        </div>
    </div>
</form>