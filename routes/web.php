<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\CetakAdendumController;
use App\Http\Controllers\CetakKartuAngsuranController;
use App\Http\Controllers\CetakMurabahahController;
use App\Http\Controllers\RealisasiWakalahController;
use App\Http\Controllers\RealisasiMurabahahController;
use App\Http\Controllers\CetakMusyarakahController;
use App\Http\Controllers\CetakSimpananLimaPersenController;
use App\Http\Controllers\CetakCsController;
use App\Http\Controllers\CetakLaRisywahController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\CetakCsWoController;
use App\Http\Controllers\CetakApprovalController;
use App\Http\Controllers\PembiayaanController;
use App\Http\Controllers\PemeliharaanKelompok;
use App\Http\Controllers\ViewDataController;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\PembatalanWakalahController;
use App\Http\Controllers\RealisasiTagihanKelompokController;
use App\Http\Controllers\SetoranLimaPersenController;
use App\Http\Controllers\RestKemampuanBayarController;
use App\Http\Controllers\RealisasiMusyarokahController;
use App\Http\Controllers\HapusBukuController;

//  jika user belum login
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'dologin']);

});

// untuk Admin AL login
Route::group(['middleware' => ['auth', 'role:1,2']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/redirect', [RedirectController::class, 'check']);
});


// untuk Admin
Route::group(['middleware' => ['auth', 'role:1']], function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/realisasi_wakalah', [RealisasiWakalahController::class, 'index']);
    Route::POST('/proses_realisasi_wakalah', [RealisasiWakalahController::class, 'realisasiWakalah']);
    Route::get('realisasi_wakalah/getData', [RealisasiWakalahController::class, 'getData']);
    Route::get('/cetak/cs', [CetakCsController::class, 'index'])->name('cetak.cs.index');
    Route::get('/cetak/kode_ao', [CetakCsController::class, 'cariAo'])->name('cetak.kode.ao');
    Route::get('/cetak/pdf_cs', [CetakCsController::class, 'pdfCs'])->name('pdfCs');
    Route::get('/cetak/cs_wo', [CetakCsWoController::class, 'index'])->name('cetak.cs.wo.index');
    Route::get('/cetak/pdf_cs_wo', [CetakCsWoController::class, 'pdfCsWo'])->name('pdfCsWo');

    Route::get('/cetak/musyarakah', [CetakMusyarakahController::class, 'index'])->name('cetak_musyarakah');
    Route::post('/cetak/musyarakah/result', [CetakMusyarakahController::class, 'hasil'])->name('form_musyarakah');
    Route::get('/cetak/larisywah', [CetakLaRisywahController::class, 'index'])->name('cetak_larisywah');
    Route::post('/cetak/larisywah/result', [CetakLaRisywahController::class, 'hasil'])->name('form_larisywah');

    Route::get('/cetak/approval', [CetakApprovalController::class, 'index'])->name('cetak_approval');
    Route::post('/cetak/approval/result', [CetakApprovalController::class, 'hasil'])->name('form_approval');
    Route::get('/realisasi/murabahah', [RealisasiMurabahahController::class, 'index'])->name('realisasi_murabahah');
    Route::get('/realisasi/murabahah/search', [RealisasiMurabahahController::class, 'search'])->name('realisasi.search');
    Route::post('/realisasi/murabahah/update', [RealisasiMurabahahController::class, 'updateStatus'])->name('realisasi.update');

    Route::get('/kelompok/data', [KelompokController::class, 'data'])->name('kelompok.data');
    Route::resource('kelompok', KelompokController::class);

    Route::get('/cetak/murabahah', [CetakMurabahahController::class, 'index'])->name('cetakMurabahah');
    Route::post('/cetak/murabahah/filter', [CetakMurabahahController::class, 'filter'])->name('cetakMurabahah.filter');
    Route::post('/cetak/murabahah/pdf', [CetakMurabahahController::class, 'cetakPDF'])->name('cetakMurabahah.pdf');

    Route::get('/cetak/simpanan-5-persen', [CetakSimpananLimaPersenController::class, 'index'])->name('cetak-simpanan-5-persen');
    Route::post('/cetak/simpanan-5-persen/filter', [CetakSimpananLimaPersenController::class, 'filter'])->name('cetakSimpanan5Persen.filter');
    Route::post('/cetak/simpanan-5-persen/pdf', [CetakSimpananLimaPersenController::class, 'cetakPDF'])->name('cetakSimpanan5Persen.pdf');

    Route::get('/cetak/adendum', [CetakAdendumController::class, 'index'])->name('cetakAdendum');
    Route::post('/cetak/adendum/filter', [CetakAdendumController::class, 'filter'])->name('cetakAdendum.filter');
    Route::post('/cetak/adendum/pdf', [CetakAdendumController::class, 'cetakPDF'])->name('cetakAdendum.pdf');

    Route::get('/cetak/kartu-angsuran', [CetakKartuAngsuranController::class, 'index'])->name('cetakkartuAngsuran');
    Route::post('/cetak/kartu-angsuran/filter', [CetakKartuAngsuranController::class, 'filter'])->name('cetakkartuAngsuran.filter');
    Route::post('/cetak/kartu-angsuran/pdf', [CetakKartuAngsuranController::class, 'cetakPDF'])->name('cetakkartuAngsuran.pdf');

    Route::get('/anggota/data', [AnggotaController::class, 'data'])->name('anggota.data');
    Route::get('/get-kelompok-data', [AnggotaController::class, 'getKelompokData']);
    Route::post('/cari-ktp', [AnggotaController::class, 'cariKtp']);
    Route::get('/anggota/export', [AnggotaController::class, 'export'])->name('anggota.export');
    Route::get('anggota/data', [AnggotaController::class, 'data'])->name('anggota.data');
    Route::resource('anggota', AnggotaController::class);
    Route::get('/get-kelompok/{cao}', [AnggotaController::class, 'getKelompokByCao']);

    Route::get('/get-anggota/{cif}', [KelompokController::class, 'getAnggotaByCif']);
    Route::get('/pembiayaan', [PembiayaanController::class, 'index'])->name('pembiayaan.index');
    Route::get('/pembiayaan/data', [PembiayaanController::class, 'data'])->name('pembiayaan.data');
    Route::post('/pembiayaan/add', [PembiayaanController::class, 'addPembiayaan'])->name('pembiayaan.add');

    Route::get('/view-data/data', [ViewDataController::class, 'data'])->name('viewData.data');
    Route::resource('view-data', ViewDataController::class);

    Route::get('/pemeliharaan-kelompok/data', [PemeliharaanKelompok::class, 'data'])->name('pemeliharaan-kelompok.data');
    Route::get('/pemeliharaan-kelompok/get-anggota', [PemeliharaanKelompok::class, 'getAnggota'])->name('pemeliharaan-kelompok.getAnggota');
    Route::resource('pemeliharaan-kelompok', PemeliharaanKelompok::class);

    Route::get('/realisasi/hapus-buku', [HapusBukuController::class, 'index'])->name('hapus_buku');
    Route::get('/realisasi/hapus-buku/search-cif', [HapusBukuController::class, 'searchCif'])->name('hapus_buku.search_cif');
    Route::post('/realisasi/hapus-buku/add-transaction', [HapusBukuController::class, 'addTransaction'])->name('hapus_buku.add_transaction');
    Route::post('/realisasi/hapus-buku/delete-transaction', [HapusBukuController::class, 'deleteTransaction'])->name('hapus_buku.delete_transaction');
    Route::post('/realisasi/hapus-buku/process-all', [HapusBukuController::class, 'processAll'])->name('hapus_buku.process_all');


    // DOMpdf
    Route::get('/pdf/generate/{feature}/{date}', [PDFController::class, 'generateMusyarakahPdf'])->name('pdf.generateMusyarakah');
    Route::get('/pdf/generate/{feature}/{kelompok}/{date}', [PDFController::class, 'generateLaRisywahPdf'])->name('pdf.generateLaRisywah');
    Route::get('/pdf/generate/{feature}/{date}', [PDFController::class, 'generateApprovalPdf'])->name('pdf.generateApproval');

    Route::get('/realisasi/pembatalan-wakalah', [PembatalanWakalahController::class, 'index'])->name('pembatalan_wakalah');
    Route::get('/realisasi/pembatalan-wakalah/data', [PembatalanWakalahController::class, 'data'])->name('pembatalan_wakalah.data');
    Route::post('/realisasi/pembatalan-wakalah/realisasi', [PembatalanWakalahController::class, 'realisasi'])->name('pembatalan_wakalah.realisasi');

    Route::get('/setoran-lima-persen', [SetoranLimaPersenController::class, 'index']);
    Route::get('/setoran-lima-persen-get-kelompok', [SetoranLimaPersenController::class, 'getSetKelompok']);
    Route::get('/setoran-lima-persen/getData', [SetoranLimaPersenController::class, 'getData']);
    Route::post('/proses-realisasi-lima-persen', [SetoranLimaPersenController::class, 'realisasiLimaPersen']);

    //Rest Kemampuan Bayar
    Route::get('/rest-kemampuan-bayar', [RestKemampuanBayarController::class, 'index']);
    Route::get('/rest-kemampuan-bayar-get-kelompok', [RestKemampuanBayarController::class, 'getSetKelompok']);
    Route::get('/rest-kemampuan-bayar/getData', [RestKemampuanBayarController::class, 'getData']);
    Route::post('/proses-rest-kemampuan-bayar', [RestKemampuanBayarController::class, 'realisasiRestKemampuanBayar']);


    // realisasi tagihan kelompok
    Route::get('/realisasi/tagihan-kelompok', [RealisasiTagihanKelompokController::class, 'index']);
    Route::post('/realisasi/tagihan-kelompok/get-kelompok', [RealisasiTagihanKelompokController::class, 'getKelompok'])->name('realisasi.tagihanKelompok.getKelompok');
    Route::post('/realisasi/tagihan-kelompok/process', [RealisasiTagihanKelompokController::class, 'processRealisasi'])->name('realisasi.tagihanKelompok.process');

    //Realisasi Musyarokah
    Route::get('/realisasi-musyarakah', [RealisasiMusyarokahController::class, 'index']);
    Route::get('/realisasi-musyarakah-get-kelompok', [RealisasiMusyarokahController::class, 'getSetKelompok']);
    Route::get('/realisasi-musyarakah/getData', [RealisasiMusyarokahController::class, 'getData']);
    Route::post('/proses-realisasi-musyarakah', [RealisasiMusyarokahController::class, 'realisasiMusyarokah']);


});

// untuk Al
Route::group(['middleware' => ['auth', 'role:2']], function () {
    Route::get('/al', [AlController::class, 'index']);

});

