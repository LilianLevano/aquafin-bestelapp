<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FloodForecast\FloodForecastController;

Route::middleware('auth')->group(function () {
    Route::prefix('technieker')
        ->name('technieker.')
        ->group(function () {
            Route::get('flood-forecast', [FloodForecastController::class, 'api']);
        });
});
