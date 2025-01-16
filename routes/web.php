<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlController;
use App\Http\Controllers\RealisasiWakalahController;
use App\Http\Controllers\RealisasiMurabahahController;

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
    Route::get('/cetak/musyarokah', [CetakMusyarokahController::class, 'index']);
    Route::get('/realisasi/murabahah', [RealisasiMurabahahController::class, 'index'])->name('realisasi_murabahah');

});

// untuk Al
Route::group(['middleware' => ['auth', 'role:2']], function () {
    Route::get('/al', [AlController::class, 'index']);

});
