<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\CetakMurabahahController;
use App\Http\Controllers\RealisasiWakalahController;
use App\Http\Controllers\RealisasiMurabahahController;
use App\Http\Controllers\CetakMusyarakahController;
use App\Http\Controllers\CetakSimpananLimaPersenController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\PDFController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
    Route::post('/cetak/musyarakah/result', [CetakMusyarakahController::class, 'hasil'])->name('form_musyarakah');
    Route::get('/realisasi/murabahah', [RealisasiMurabahahController::class, 'index'])->name('realisasi_murabahah');

    Route::get('/kelompok/data', [KelompokController::class, 'data'])->name('kelompok.data');
    Route::resource('kelompok', KelompokController::class);

    Route::get('/cetak/murabahah', [CetakMurabahahController::class, 'index'])->name('cetak_murabahah');
    Route::post('/cetak-murabahah/filter', [CetakMurabahahController::class, 'filter'])->name('cetak-murabahah.filter');
    Route::post('/cetak-murabahah/pdf', [CetakMurabahahController::class, 'cetakPDF'])->name('cetak-murabahah.pdf');

    Route::get('/cetak/simpanan-5-persen', [CetakSimpananLimaPersenController::class, 'index'])->name('cetak-simpanan-5-persen');
    Route::post('/simpanan-5-persen/filter', [CetakSimpananLimaPersenController::class, 'filter'])->name('cetakSimpanan5Persen.filter');
    Route::post('/simpanan-5-persen/pdf', [CetakSimpananLimaPersenController::class, 'cetakPDF'])->name('cetakSimpanan5Persen.pdf');


    
    Route::get('anggota/data', [AnggotaController::class, 'data'])->name('anggota.data');
    Route::resource('anggota', AnggotaController::class);
    Route::get('/proxy/search', function (Request $request) {
        $ktp = $request->query('ktp');
        $response = Http::get("http://185.201.9.210/apimobcol/rmc.php?ktp=$ktp");
        return $response->json(); // Return JSON langsung ke frontend
    })->name('proxy.search');

});

// untuk Al
Route::group(['middleware' => ['auth', 'role:2']], function () {
    Route::get('/al', [AlController::class, 'index']);

});

// DOMpdf
Route::get('/pdf/generate/{feature}/{date}', [PDFController::class, 'generateMusyarakahPdf'])->name('pdf.generate');