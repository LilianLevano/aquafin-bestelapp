<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\HelpRequestController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

// Guest Routes

Route::resource('help-request', HelpRequestController::class)->names('help-request');
// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->middleware('verified')->name('/');

    Route::get('/categories', function () {
        return view('categories.index');
    })->name('categories');

    // Admin Routes
    Route::middleware('role:Admin')->group(function () {
        Route::prefix('admin')
            ->name('admin.')
            ->group(function () {
                Route::resource('accounts', AccountController::class);
                Route::resource('roles', RoleController::class)->except(['show']);
                Route::resource('materials', MaterialController::class);
                Route::resource('categories', CategoryController::class)->except(['show']);
            });

        Route::get('/help-requests', function () {
            return view('help-requests.index');
        })->name('help-requests');
    });

    // Technician Routes
    Route::middleware('role:Technieker')->group(function () {
        Route::prefix('technieker')->group(function () {
            Route::resource('orders', OrderController::class);
            Route::resource('materials', MaterialController::class)->only('show');
        });
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

