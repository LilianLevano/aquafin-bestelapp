<?php

use App\Http\Controllers\Models\UserController;
use App\Http\Controllers\Models\RoleController;
use App\Http\Controllers\Models\MaterialController;
use App\Http\Controllers\Models\HelpRequestController;
use App\Http\Controllers\Models\OrderController;
use App\Http\Controllers\Models\AddressController;
use App\Http\Controllers\Models\CategoryController;
use App\Http\Controllers\Models\SiteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FloodForecastController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Guest Routes
Route::resource('help-requests', HelpRequestController::class)->only(['create', 'store']);
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('home', function () {
        $roleName = Auth::user()->role->name;
        $routeName = strtolower($roleName . '.home');

        if (Route::has($routeName)) {
            return redirect()->route($routeName);
        }
        return redirect()->route('login');
    })->name('home');

    // Admin
    Route::middleware('role:Admin')->group(function () {
        Route::prefix('admin')
            ->name('admin.')
            ->group(function () {
                Route::resource('accounts', UserController::class);
                Route::resource('roles', RoleController::class)->except(['show']);
                Route::resource('materials', MaterialController::class);
                Route::resource('help-requests', HelpRequestController::class)->except(['store']);
                Route::resource('addresses', AddressController::class);
                Route::resource('categories', CategoryController::class)->except(['show']);
                // Route::resource('sites', SiteController::class)->except(['store']);
                Route::get('home', function () {
                    return redirect()->route('admin.accounts.index');
                })->name('home');
            });
    });

    // Technician
    Route::middleware('role:Technieker')->group(function () {
        Route::prefix('technieker')
            ->name('technieker.')
            ->group(function () {
                Route::resource('orders', OrderController::class);
                Route::resource('flood-forecast', FloodForecastController::class)->except(['api']);
                Route::resource('materials', MaterialController::class)->only('show');
                Route::get('home', function () {
                    return redirect()->route('technieker.orders.index');
                })->name('home');
            });
    });

    // Manager
    Route::middleware('role:Manager')->group(function () {
        Route::prefix('manager')
            ->name('manager.')
            ->group(function () {
                Route::resource('orders', OrderController::class)->only(['index', 'show']);
                Route::get('home', function () {
                    return redirect()->route('manager.orders.index');
                })->name('home');
        });
    });

    // Profile
    Route::resource('profile', ProfileController::class);
});

require __DIR__.'/auth.php';
