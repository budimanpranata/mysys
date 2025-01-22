<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlController;
use App\Http\Controllers\RealisasiWakalahController;
use App\Http\Controllers\RealisasiMurabahahController;
use App\Http\Controllers\CetakMusyarakahController;
use App\Http\Controllers\CetakCsController;
use App\Http\Controllers\CetakLaRisywahController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\PDFController;

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
    Route::get('/cetak/musyarakah', [CetakMusyarakahController::class, 'index'])->name('cetak_musyarakah');
    Route::get('/cetak/cs', [CetakCsController::class, 'index'])->name('cetak.cs.index');
    Route::get('/cetak/kode_ao', [CetakCsController::class, 'cariAo'])->name('cetak.kode.ao');
    Route::get('/cetak/pdf_cs', [CetakCsController::class, 'pdfCs'])->name('pdfCs');

    Route::post('/cetak/musyarakah/result', [CetakMusyarakahController::class, 'hasil'])->name('form_musyarakah');
    Route::get('/realisasi/murabahah', [RealisasiMurabahahController::class, 'index'])->name('realisasi_murabahah');
    Route::get('/cetak/larisywah', [CetakLaRisywahController::class, 'index'])->name('cetak_larisywah');
    Route::post('/cetak/larisywah/result', [CetakLaRisywahController::class, 'hasil'])->name('form_larisywah');
    Route::get('/kelompok/data', [KelompokController::class, 'data'])->name('kelompok.data');
    Route::resource('kelompok', KelompokController::class);

});

// untuk Al
Route::group(['middleware' => ['auth', 'role:2']], function () {
    Route::get('/al', [AlController::class, 'index']);

});

// DOMpdf

Route::get('/pdf/generate/{feature}/{date}', [PDFController::class, 'generateMusyarakahPdf'])->name('pdf.generateMusyarakah');
Route::get('/pdf/generate/{feature}/{kelompok}/{date}', [PDFController::class, 'generateLaRisywahPdf'])->name('pdf.generateLaRisywah');

