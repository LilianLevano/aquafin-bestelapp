<?php

use App\Http\Controllers\AdminMateriaalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AanvraagController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::prefix('admin')->name('admin.')->middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        Route::resource('accounts', \App\Http\Controllers\Admin\AdminAccountsController::class);
        Route::resource('rollen', \App\Http\Controllers\Admin\AdminRollenController::class);
    });

    Route::prefix('technieker')->name('technieker.')->middleware(\App\Http\Middleware\TechniekerMiddleware::class)->group(function () {
        Route::resource('bestelling', \App\Http\Controllers\TechniekerBestellingController::class);
    });
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/catalogus', [AdminMateriaalController::class, 'index']);

Route::get('/catalogus/aanmaken', [AdminMateriaalController::class, 'create']);
Route::post('/catalogus/aanmaken', [AdminMateriaalController::class, 'store']);

Route::get('/admin/catalogus', function () {
    return view('admin-catalogus');
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
<<<<<<< HEAD
});Route::prefix('technieker')->name('technieker.')->middleware(\App\Http\Middleware\TechniekerMiddleware::class)->group(function () {
    Route::resource('bestelling', \App\Http\Controllers\TechniekerBestellingController::class);

    // ✅ ADD THIS LINE:
    Route::get('/weersomstandigheden', function () {
        return view('weersomstandigheden');
    })->name('weersomstandigheden');
});
=======
});

Route::get('/technieker/bestellen', function () {
    $sites = \App\Models\Site::all();
    $materialen = \App\Models\Materiaal::all();
    return view('technieker-bestellen', compact('sites', 'materialen'));
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
>>>>>>> c1de3f6514799830ffd2308ebcb68d01e342d048
