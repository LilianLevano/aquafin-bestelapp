<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AanvraagController;



Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('accounts', \App\Http\Controllers\Admin\AdminAccountsController::class);
    Route::resource('rollen', \App\Http\Controllers\Admin\AdminRollenController::class);
});

Route::middleware('auth')->group(function () {
});
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/admin/catalogus/materiaal', function () {
    return view('admin-catalogus-materiaal');
});

Route::get('/catalogus', function () {
    return view('materiaal-catalogus');
});

Route::get('/manager', function () {
    return view('manager-welkom');
});

Route::get('/manager/bestellingen', function () {
    return view('manager-bestellingen');
});

Route::get('/manager/bestelling-detail', function () {
    return view('manager-bestelling-detail');
});

Route::get('/technieker', function () {
    return view('technieker-welkom');
});

Route::get('/technieker/bestellen', function () {
    return view('technieker-bestellen');
});

Route::get('/technieker/catalogus', function () {
    return view('technieker-catalogus');
});

Route::get('/magazijnier', function () {
    return view('magazijnier-welkom');
});

Route::get('/magazijnier/bestellingen', function () {
    return view('magazijnier-bestellingen');
});

Route::get('/admin/catalogus', function () {
    return view('admin-catalogus');
});

Route::get('/admin/catalogus/edit', function () {
    return view('admin-catalogus-edit');
});

Route::get('/admin/aanvragen', function () {
    return view('admin-aanvragen');
});

Route::get('/admin/antwoord', function () {
    return view('admin-aanvraag-antwoord');
});

Route::get('/magazijnier/bestellingen', function () {
    return view('magazijnier-bestellingen');
});