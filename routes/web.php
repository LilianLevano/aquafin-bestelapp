<?php

use App\Http\Controllers\Admin\AccountsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\HelpRequestController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
require __DIR__.'/auth.php';

// Guest Routes
Route::post('/hulp', [HelpRequestController::class, 'store'])->name('hulp.store');

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
                Route::resource('accounts', AccountsController::class)->except(['show']);
                Route::resource('roles', RolesController::class)->except(['show']);
            });

        Route::get('/materials', function () {
            return view('materials.index');
        })->name('materials');

        Route::get('/help-requests', function () {
            return view('help-requests.index');
        })->name('help-requests');
    });

    // Technician Routes
    Route::middleware('role:Technieker')->group(function () {
        Route::get('/orders', function () {
            return view('orders.index');
        })->name('orders.index');

        Route::get('/orders-create', function () {
            return view('orders.create');
        })->name('orders.create');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
