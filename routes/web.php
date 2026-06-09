<?php

use \App\Http\Middleware\AdminMiddleware;
use \App\Http\Controllers\Admin\AdminAccountsController;
use \App\Http\Controllers\Admin\AdminRollenController;
use App\Http\Controllers\AanvraagController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
require __DIR__.'/auth.php';

// Guest Routes
Route::post('/hulp', [AanvraagController::class, 'store'])->name('hulp.store');

Route::get('/catalogus', function () {
    return view('materiaal-catalogus');
});

// Protected Routes
// Dashboard (requires authentication + verification)
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');
});

// Admin Routes (requires authentication + admin middleware)
Route::middleware('auth')->group(function () {
    Route::prefix('admin')
    ->name('admin.')
    ->middleware(AdminMiddleware::class)
    ->group(function () {
        Route::resource('accounts', AdminAccountsController::class)->except(['show']);
        Route::resource('rollen', AdminRollenController::class)->except(['show']);
    });
    Route::get('/admin/catalogus/materiaal', function () {
        return view('admin-catalogus-materiaal');
    });

    Route::get('/admin/catalogus/materiaal', function () {
        return view('admin-catalogus-materiaal');
    });

    Route::get('/besteklijst', function () {
        return view('besteklijst');
    });

    Route::get('/technieker', function () {
        return view('technieker-welkom');
    });

    Route::get('/admin/aanvragen', function () {
        return view('admin-aanvragen');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
