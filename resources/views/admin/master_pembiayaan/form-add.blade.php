<div class="modal fade" id="add-form" tabindex="-1" role="dialog" aria-hidden="true">
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
                  <input type="text" class="form-control" name="unit" id="unit" value=""
                    placeholder="Masukkan Kode Unit" readonly>
                </div>
              </div>

              <div class="form-group row">
                <span class="col-sm-4 col-form-label">Produk <span class="text-danger">*</span></span>
                <div class="col-sm-7">
                  <select name="produk" id="produk" class="form-control" required>
                    <option hidden value="">-- Pilih Produk --</option>
                    <option value=1>1</option>
                    <option value=2>2</option>
                  </select>
                  <span class="help-block with-errors text-danger"></span>
                </div>
                          
              </div>

              <div class="form-group row">
                <span class="col-sm-4 col-form-label">No. Rekening <span class="text-danger">*</span></span>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="no_rek" id="no_rek" value=""
                    placeholder="Masukkan No. Rekening" readonly>
                </div>
              </div>

              <div class="form-group row">
                <span class="col-sm-4 col-form-label">CIF <span class="text-danger">*</span></span>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="cif" id="cif" value="" placeholder="Masukkan CIF"
                    readonly>
                </div>
              </div>

              <div class="form-group row">
                <span class="col-sm-4 col-form-label">Pengajuan <span class="text-danger">*</span></span>
                <div class="col-sm-7">
                  <input type="number" class="form-control" name="pengajuan" id="pengajuan"
                    placeholder="Masukkan Nominal Pengajuan" required>
                  <span class="help-block with-errors text-danger"></span>
                </div>
              </div>

              <div class="form-group row">
                <span class="col-sm-4 col-form-label">Tenor <span class="text-danger">*</span></span>
                <div class="col-sm-7">
                  <select name="tenor" id="tenor" class="form-control" required>
                    <option hidden value="">-- Pilih Tenor --</option>
                    <option value=25>25</option>
                    <option value=35>35</option>
                    <option value=50>50</option>
                  </select>
                  <span class="help-block with-errors text-danger"></span>
                </div>
              </div>

              <div class="form-group row">
                <span class="col-sm-4 col-form-label">Disetujui <span class="text-danger">*</span></span>
                <div class="col-sm-7">
                  <input type="number" class="form-control" name="disetujui" id="disetujui"
                    placeholder="Masukkan Nominal Persetujuan" required>
                  <span class="help-block with-errors text-danger"></span>
                </div>
              </div>

            </div>

            <div class="col-md-6">
              <div class="form-group row">
                <span class="col-sm-4 col-form-label">Tanggal Wakalah <span class="text-danger">*</span></span>
                <div class="col-sm-7">
                  <input type="date" class="form-control" name="tgl_wakalah" id="tgl_wakalah"
                    placeholder="Masukkan Tanggal Wakalah" required>
                  <span class="help-block with-errors text-danger"></span>
                </div>
              </div>

              <div class="form-group row">
                <span class="col-sm-4 col-form-label">Tanggal Akad <span class="text-danger">*</span></span>
                <div class="col-sm-7">
                  <input type="date" class="form-control" name="tgl_akad" id="tgl_akad"
                    placeholder="Masukkan Tanggal Akad" required>
                  <span class="help-block with-errors text-danger"></span>
                </div>
              </div>

              <div class="form-group row">
                <span class="col-sm-4 col-form-label">Bidang Usaha <span class="text-danger">*</span></span>
                <div class="col-sm-7">
                  <select name="bidang_usaha" id="bidang_usaha" class="form-control" required>
                    <option hidden value="">-- Pilih Bidang Usaha --</option>
                    <option value="Perdagangan">Perdagangan</option>
                    <option value="Perternakan">Perternakan</option>
                    <option value="Pertanian">Pertanian</option>
                    <option value="Produsen">Produsen</option>
                    <option value="Jasa">Jasa</option>
                  </select>
                  <span class="help-block with-errors text-danger"></span>
                </div>
              </div>

              <div class="form-group row">
                <span class="col-sm-4 col-form-label">Keterangan Usaha <span class="text-danger">*</span></span>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="keterangan_usaha" id="keterangan_usaha"
                    placeholder="Masukkan Keterangan Usaha" required>
                  <span class="help-block with-errors text-danger"></span>
                </div>
              </div>

            </div>

            <!-- Hidden input to store id -->
            <input type="hidden" name="id" id="userId" value="{{ Auth::user()->role_id }}" required>
            <!-- Hidden input to store param tanggal -->
            <input type="hidden" name="param_tanggal" id="userDate" value="{{ Auth::user()->param_tanggal }}" required>
            <!-- Hidden input to store code ao -->
            <input type="hidden" name="cao" id="cao" value="" required>
            <!-- Hidden input to store code kelompok -->
            <input type="hidden" name="code_kel" id="code_kel" value="" required>
            <!-- Hidden input to store nama anggota -->
            <input type="hidden" name="nama" id="nama" value="" required>
            <!-- Hidden input to store tanggal lahir -->
            <input type="hidden" name="tgl_lahir" id="tgl_lahir" value="" required>
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