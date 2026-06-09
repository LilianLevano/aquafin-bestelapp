<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AanvraagController;



Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');



Route::middleware('auth')->group(function () {

    Route::prefix('admin')->name('admin.')->middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        Route::resource('accounts', \App\Http\Controllers\Admin\AdminAccountsController::class);
        Route::resource('rollen', \App\Http\Controllers\Admin\AdminRollenController::class);
    });

    Route::prefix('technieker')->name('technieker.')->middleware(\App\Http\Middleware\TechniekerMiddleware::class)->group(function () {
        Route::resource('bestelling', \App\Http\Controllers\TechniekerBestellingController::class);
    });




});
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/admin/catalogus/materiaal', function () {
    return view('admin-catalogus-materiaal');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/catalogus', function () {
    return view('materiaal-catalogus');
});