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
Route::get('/', function () {
	return view('dashboard');
})->middleware(['auth']);
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Admin Routes (requires authentication + admin middleware)
Route::middleware('auth')->group(function () {
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(AdminMiddleware::class)
        ->group(function () {
            Route::resource('accounts', AdminAccountsController::class)->except(['show']);
            Route::resource('rollen', AdminRollenController::class)->except(['show']);
        });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin/catalogus/materiaal', function () {
    return view('admin-catalogus-materiaal');
});
