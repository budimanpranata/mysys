<form method="POST" action="{{ url('/al/approval-pengajuan/update', $detail->no_anggota) }}">
    <div class="card-body">
        @method('PUT')
        @csrf
        <div class="card">
            <div class="card-header">
                Detail Anggota Pengajuan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Kode Unit</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="unit" id="unit" value="{{ $detail->unit }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">CIF</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="cif" id="cif" value="{{ $detail->cif }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Nama</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="nama" value="{{ $detail->nama }}" id="nama">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">No Identitas</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $detail->anggota->ktp  ?? '' }}" id="nikInput">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" onclick="cariKtp()">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Tempat Lahir</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"
                                    value="{{ $detail->anggota->tempat_lahir ?? '' }}" id="tempat_lahir">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control"
                                    value="{{ $detail->anggota->tgl_lahir ?? '' }}" id="tgl_lahir">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Alamat</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"
                                    value="{{ $detail->anggota->alamat ?? '' }}" id="alamat">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">RT / RW</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"
                                    value="{{ $detail->anggota->rtrw ?? '' }}" id="rtrw">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Kode Pos</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"
                                    value="{{ $detail->anggota->kode_pos ?? '' }}" id="kode_pos">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Kelurahan</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"
                                    value="{{ $detail->anggota->desa ?? '' }}" id="desa">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Kecamatan</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"
                                    value="{{ $detail->anggota->kecamatan ?? '' }}" id="kecamatan">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Kabupaten / Kota</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"
                                    value="{{ $detail->anggota->kota ?? '' }}" id="kota">
                            </div>
                        </div>

                        
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Kode AO</label>
                            <div class="col-sm-8">
                                <select name="cao" id="cao" class="form-control">
                                    <option value="">-- Pilih AO --</option>
                                    @foreach ($ao as $item)
                                        <option value="{{ $item->cao }}"
                                            {{ optional($detail->anggota)->cao ?? '' == $item->cao ? 'selected' : '' }}>
                                            {{ $item->cao }} - {{ $item->nama_ao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Kode Kelompok</label>
                            <div class="col-sm-8">
                                <select name="kode_kel" id="kode_kel" class="form-control">
                                    <option value="">-- Pilih Kelompok --</option>
                                    @foreach ($kelompok as $item)
                                        <option value="{{ $item->code_kel }}"
                                            {{ optional($detail->anggota)->kode_kel ?? '' == $item->code_kel ? 'selected' : '' }}>
                                            {{ $item->code_kel }} - {{ $item->nama_kel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Kewarganegaraan </label>
                            <div class="col-sm-8">
                                <select name="kewarganegaraan" id="kewarganegaraan" class="form-control">
                                    <option hidden value="">-- PILIH KEWARGANEGARAAN --</option>
                                    <option selected value="Indonesia">INDONESIA</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Agama</label>
                            <div class="col-sm-8">
                                <select name="agama" id="agama" class="form-control">
                                    <option hidden value="">-- PILIH AGAMA --</option>
                                    <option value="Islam"
                                        {{ optional($detail->anggota)->agama ?? '' == 'Islam' ? 'selected' : '' }}>
                                        ISLAM
                                    </option>
                                    <option value="Kristen"
                                        {{ optional($detail->anggota)->agama ?? '' == 'Kristen' ? 'selected' : '' }}>
                                        KRISTEN
                                    </option>
                                    <option value="Hindu"
                                        {{ optional($detail->anggota)->agama ?? '' == 'Hindu' ? 'selected' : '' }}>
                                        HINDU
                                    </option>
                                    <option value="Buddha"
                                        {{ optional($detail->anggota)->agama ?? '' == 'Buddha' ? 'selected' : '' }}>
                                        BUDDHA
                                    </option>
                                    <option value="Konghucu"
                                        {{ optional($detail->anggota)->agama ?? '' == 'Konghucu' ? 'selected' : '' }}>
                                        KONGHUCU
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Pendidikan </label>
                            <div class="col-sm-8">
                                <select name="pendidikan" id="pendidikan" class="form-control @error('pendidikan') is-invalid @enderror">
                                    <option hidden value="">-- PILIH PENDIDIKAN --</option>
                                    <option value="SD"
                                    @if (optional($detail->anggota)->pendidikan ?? '' == 'SD')
                                        selected
                                    @endif
                                    >SD</option>
                                    <option value="SMP"
                                    @if (optional($detail->anggota)->pendidikan ?? '' == 'SMP')
                                        selected
                                    @endif
                                    >SMP</option>
                                    <option value="SMA"
                                    @if (optional($detail->anggota)->pendidikan ?? '' == 'SMA')
                                        selected
                                    @endif
                                    >SMA</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Status Menikah</label>
                            <div class="col-sm-8">
                                <select id="status_menikah" class="form-control">
                                    <option hidden value="">-- PILIH STATUS PERKAWINAN --</option>
                                    <option value="Menikah"
                                        {{ optional($detail->anggota)->status_menikah ?? '' == 'Menikah' ? 'selected' : '' }}>
                                        MENIKAH
                                    </option>
                                    <option value="Belum Menikah"
                                        {{ optional($detail->anggota)->status_menikah ?? '' == 'Belum Menikah' ? 'selected' : '' }}>
                                        BELUM MENIKAH
                                    </option>
                                    <option value="Janda"
                                        {{ optional($detail->anggota)->status_menikah ?? '' == 'Janda' ? 'selected' : '' }}>
                                        JANDA
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Nama Pasangan/Penjamin </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ optional($detail->anggota)->waris }}" id="waris"
                                    placeholder="Masukkan Nama Pasangan">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Pekerjaan Pasangan </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ optional($detail->anggota)->pekerjaan_pasangan }}" id="pekerjaan_pasangan"
                                    placeholder="Masukkan Pekerjaan Pasangan">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">No. Telp </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="no_hp" id="no_hp" value="{{ optional($detail->anggota)->no_hp }}"
                                    placeholder="Masukkan No. Telp">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">No. Telp Pasangan </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="hp_pasangan" id="hp_pasangan" value="{{ optional($detail->anggota)->hp_pasangan }}"
                                    placeholder="Masukkan No. Telp Pasangan">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Nama Ibu Kandung</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="ibu_kandung"
                                    value="{{ optional($detail->anggota)->ibu_kandung }}"
                                    id="ibu_kandung">
                            </div>
                        </div>

                    </div>

                    <!-- View Gambar -->
                    <div class="col-12 mt-3" id="resultContainer"></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Detail Pembiyaan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">No Rekening</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="no_anggota" value="{{ $detail->no_anggota }}" id="no_anggota">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Pengajuan</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="plafond" value="{{ $detail->plafond }}" id="plafond">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Tenor</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="tenor" value="{{ $detail->tenor }}" id="tenor">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Disetujui</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="plafond" value="{{ $detail->plafond }}" id="plafond">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Margin</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="saldo_margin" value="{{ $detail->saldo_margin }}" id="saldo_margin">
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Sisa Pembiayaan</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="saldo_margin" value="{{ $detail->saldo_margin }}" id="saldo_margin">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Sisa Margin</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="saldo_margin" value="{{ $detail->saldo_margin }}" id="saldo_margin">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Angsuran</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="angsuran" value="{{ $detail->angsuran }}" id="angsuran">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Harga Jual</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="plafond" value="{{ $detail->plafond }}" id="plafond">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Setoran</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="bulat" value="{{ $detail->bulat }}" id="bulat">
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="card-footer">
        <button class="btn btn-success" type="button" id="btnApprove">
            <i class="fas fa-check"></i> Approve
        </button>
        <button class="btn btn-warning" type="submit" id="btnSave">
            <i class="fas fa-edit"></i> Edit
        </button>
    </div>
</form>