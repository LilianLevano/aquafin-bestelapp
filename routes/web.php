<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bestelformulier', function () {
    return view('bestel-formulier');
});

Route::get('/catalogus', function () {
    return view('materiaal-catalogus');
});
