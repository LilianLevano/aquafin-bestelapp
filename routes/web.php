<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('accounts', \App\Http\Controllers\Admin\AdminAccountsController::class);
});

Route::middleware('auth')->group(function () {





});
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/bestelformulier', function () {
    return view('bestel-formulier');
});

Route::get('/catalogus', function () {
    return view('materiaal-catalogus');
}); 

Route::get('/home', function () {
    return view('home');
})->middleware('auth')->name('home');
