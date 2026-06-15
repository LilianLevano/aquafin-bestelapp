<?php

use App\Http\Controllers\Models\UserController;
use App\Http\Controllers\Models\RoleController;
use App\Http\Controllers\Models\MaterialController;
use App\Http\Controllers\Models\HelpRequestController;
use App\Http\Controllers\Models\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Guest Routes
Route::post('help-requests', [HelpRequestController::class, 'store'])->name('help-requests.store');
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
                Route::resource('accounts', UserController::class)->except(['show']);
                Route::resource('roles', RoleController::class)->except(['show']);
                Route::resource('materials', MaterialController::class);
                Route::resource('help-requests', HelpRequestController::class)->except(['store']);
                Route::get('categories', function () {
                    return view('categories.index');
                })->name('categories');
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
                Route::resource('orders', OrderController::class)->except(['show']);
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
    Route::get('/profile', function() {
        $user = Auth::user();
        return app(ProfileController::class)->edit($user);
    })->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
