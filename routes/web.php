<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\HelpRequestController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BestellingController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

// Guest Routes

Route::resource('help-request', HelpRequestController::class)->names('help-request')->only(['create', 'store']);
// Protected Routes
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => view('dashboard'))
        ->middleware('verified')
        ->name('/');

    Route::get('/categories', fn() => view('categories.index'))
        ->name('categories');

    // ── Admin ──
    Route::middleware('role:Admin')->group(function () {
        Route::prefix('admin')
            ->name('admin.')
            ->group(function () {
                Route::resource('accounts', AccountController::class);
                Route::resource('roles', RoleController::class)->except(['show']);
                Route::resource('materials', MaterialController::class);
                Route::resource('categories', CategoryController::class)->except(['show']);
                Route::get('/help-requests/{is_completed}', [HelpRequestController::class, 'index'])->name('help-requests.index');
                Route::get('/help-requests/show/{id}', [HelpRequestController::class, 'show'])->name('help-requests.show');
                Route::resource('help-requests', HelpRequestController::class)->except(['index']);

            });
    });

    // ── Technieker ──
    Route::middleware('role:Technieker')->group(function () {
        Route::prefix('technieker')->group(function () {
            Route::resource('orders', OrderController::class);
            Route::resource('materials', MaterialController::class)->only('show');
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
