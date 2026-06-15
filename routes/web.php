<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\HelpRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BestellingController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

// ── Gast routes ──────────────────────────────────────────────────────────────
Route::post('/hulp', [HelpRequestController::class, 'store'])->name('hulp.store');

// ── Beveiligde routes ─────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => view('dashboard'))
        ->middleware('verified')
        ->name('/');

    Route::get('/categories', fn() => view('categories.index'))
        ->name('categories');

    // ── Admin ──
    Route::middleware('role:Admin')->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('accounts', AccountController::class)->except(['show']);
            Route::resource('roles',    RoleController::class)->except(['show']);
            Route::resource('materials', MaterialController::class);
        });

        Route::get('/help-requests', fn() => view('help-requests.index'))
            ->name('help-requests');
    });

    // ── Technieker ──
    Route::middleware('role:Technieker')->group(function () {
        Route::prefix('technieker')->group(function () {
            Route::resource('orders', OrderController::class)->except(['show']);
        });
    });

    // ── Manager ──
    Route::middleware('role:Manager')->group(function () {
        Route::get('/bestellingen', [BestellingController::class, 'index'])
            ->name('bestellingen.index');
        Route::get('/bestellingen/{id}', [BestellingController::class, 'show'])
            ->name('bestellingen.show');
    });

    // ── Profiel ──
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});