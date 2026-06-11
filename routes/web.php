<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\HelpRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
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
                Route::resource('accounts', AccountController::class)->except(['show']);
                Route::resource('roles', RoleController::class)->except(['show']);
                Route::resource('materials', MaterialController::class);
            });

        Route::get('/help-requests', function () {
            return view('help-requests.index');
        })->name('help-requests');
    });

    // Technician Routes
    Route::middleware('role:Technieker')->group(function () {
        Route::prefix('technieker')->group(function () {
            Route::resource('orders', OrderController::class)->except(['show']);
        });
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('api')->name('api.')
    ->group(function () {
        Route::get('/flood-forecast', function () {
            return view('');
        });

        Route::get('/flood-forecast/{years_ahead}', function ($years_ahead) {
            return view('');
        });

        Route::get('/risk-months', function () {
            return view('');
        });

        Route::get('/risk-months/{years_ahead}', function ($years_ahead) {
            return view('');
        });

        Route::post('/flood-forecast/refresh', function () {
            return view('');
        });
    }
);
