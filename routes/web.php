<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AanvraagController;



Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('accounts', \App\Http\Controllers\Admin\AdminAccountsController::class);
});

Route::middleware('auth')->group(function () {





});
Route::get('/', function () {
    return view('welcome');
});

Route::get('/bestelformulier', function () {
    return view('bestel-formulier');
});

Route::get('/catalogus', function () {
    return view('materiaal-catalogus');
});
