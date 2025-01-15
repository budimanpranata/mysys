<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlController;
use Illuminate\Support\Facades\Auth;

//  jika user belum login
Route::group(['middleware' => 'guest'], function() {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'dologin']);

});

// untuk Admin
Route::group(['middleware' => ['auth', 'role:1,2']], function() {
    Route::POST('/logout', [AuthController::class, 'logout']);
    Route::POST('/redirect', [RedirectController::class, 'check']);
});


// untuk AL
Route::group(['middleware' => ['auth', 'role:1']], function() {
    Route::get('/admin', [AdminController::class, 'index']);
});

// untuk Al
Route::group(['middleware' => ['auth', 'role:2']], function() {
    Route::get('/al', [AlController::class, 'index']);

});
