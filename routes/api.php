<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FloodForecast\FloodForecastController;

Route::middleware('auth')->group(function () {
    Route::get('flood-forecast', [FloodForecastController::class, 'api']);

    Route::get('flood-forecast/{days_ahead}', [FloodForecastController::class, 'api']);

    Route::get('risk-months', [FloodForecastController::class, 'api']);

    Route::get('risk-months/{days_ahead}', [FloodForecastController::class, 'api']);

    Route::post('flood-forecast/refresh', [FloodForecastController::class, 'api']);
});
