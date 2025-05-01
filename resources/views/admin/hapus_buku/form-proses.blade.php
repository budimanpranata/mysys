<!-- Modal -->
<div class="modal fade" id="prosesModal" tabindex="-1" aria-labelledby="prosesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="prosesModalLabel">Form Proses Hapus Buku</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formProses">
          <div class="form-group mb-3">
            <label for="nomor_bukti">Nomor Bukti</label>
            <input type="text" class="form-control" id="nomor_bukti" name="nomor_bukti" readonly>
          </div>

          <div class="form-group mb-3">
            <label for="tanggal">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
          </div>

          <div class="form-group mb-3">
            <label for="cif">CIF</label>
            <input type="text" class="form-control" id="modal_cif" name="cif" readonly>
          </div>

          <div class="form-group mb-3">
            <label for="pokok">Pokok</label>
            <input type="number" class="form-control" id="pokok" name="pokok" required>
          </div>

          <div class="form-group mb-3">
            <label for="margin">Margin</label>
            <input type="number" class="form-control" id="margin" name="margin" required>
          </div>

          <div class="form-group mb-3">
            <label for="minggu_ke">Minggu-ke</label>
            <input type="number" class="form-control" id="minggu_ke" name="minggu_ke" required>
          </div>

          <div class="form-group mb-3">
            <label for="simpanan">Simpanan</label>
            <input type="number" class="form-control" id="simpanan" name="simpanan" required>
          </div>

          <div class="form-group mb-3">
            <label for="jenis_wo">Jenis WO</label>
            <select class="form-control" id="jenis_wo" name="jenis_wo" required>
              <option value="">Pilih Jenis WO</option>
              <option value="NPF">NPF</option>
              <option value="Meninggal Dunia">Meninggal Dunia</option>
            </select>
          </div>

          <!-- Hidden input to store code ao -->
          <input type="hidden" name="no_anggota" id="no_anggota" value="" required>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="submitProses">Proses</button>
      </div>
    </div>
  </div>
</div>