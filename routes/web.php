<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\HelpRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FloodForecast\FloodForecastController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

    // Flood forecasts
    Route::get('/flood-forecast', [FloodForecastController::class, 'index'])
        ->name('flood-forecast');
    Route::get('/flood-forecast/{days_ahead}', [FloodForecastController::class, 'index'])
        ->name('flood-forecast.days_ahead');
    Route::get('/risk-months', [FloodForecastController::class, 'index'])
        ->name('risk-months');
    Route::get('/risk-months/{days_ahead}', [FloodForecastController::class, 'index'])
        ->name('risk-months.days_ahead');
    Route::post('/flood-forecast/refresh', [FloodForecastController::class, 'index'])
        ->name('flood-forecast.refresh');

    // Catalogue
    Route::get('/catalogus', function () {
        return view('materiaal-catalogus');
    });
});

require __DIR__.'/auth.php';
